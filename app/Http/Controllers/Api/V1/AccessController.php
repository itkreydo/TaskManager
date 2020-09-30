<?php


namespace App\Http\Controllers\Api\V1;


use App\File;
use App\Group;
use App\Http\Controllers\Controller;
use App\Post;
use App\Services\KeyService\KeyStore;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccessController extends Controller
{
    private static $allowedTypes = [
        'enable-two-fa',
        'disable-two-fa'
    ];

    private $jwtLib;
    private $keyStore;
    public function __construct()
    {
//        $this->jwtLib = app(JWT::class);
        $this->jwtLib = new JWT();
        $this->keyStore = new KeyStore();
    }

    public function getAccess(Request $request) {
        $password = $request->get('password');
        $type = $request->get('access_type');

        if (!in_array($type, $this::$allowedTypes)||!Hash::check($password, Auth::user()->password)) {
            throw new \InvalidArgumentException('Доступ запрещён');
        }

        $payload = [
            'access_type' => $type,
            'user_id' => Auth::user()->id,
        ];
        $signedToken = $this->jwtLib::encode($payload, $this->keyStore::getKeys()->getPrivateKey());
        return response()->json(['token' => $signedToken]);
    }

    public function loadAvatar(\Illuminate\Http\Request $request) {
        $uploadedFile = $request->file('file');

        $path = "avatars/{$uploadedFile->hashName()}";

        Storage::disk('private')->put($path, file_get_contents($uploadedFile));

        $file = new File();
        $file->path = $path;
        $file->size = $uploadedFile->getSize();
        $file->type = $uploadedFile->getMimeType();
        $file->owner_id = Auth::user()->id;
        $file->save();

        $user = Auth::user();
        $user->avatar_id = $file->id;
        $user->save();

        return response($file);
    }

}
