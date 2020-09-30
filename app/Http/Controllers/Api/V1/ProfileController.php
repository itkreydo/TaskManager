<?php


namespace App\Http\Controllers\Api\V1;


use App\Exceptions\BadApiRequestException;
use App\File;
use App\Group;
use App\Http\Controllers\Controller;
use App\Post;
use App\UsersSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class ProfileController extends Controller
{
    public function index(Request $request) {
        return Auth::user()->with('avatars')->first();
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

    public function changePassword(\Illuminate\Http\Request $request) {
        $passOld = $request->get('password_old');
        $passNew = $request->get('password');
        $passNewConfirm = $request->get('password_confirm');

        if ($passNew != $passNewConfirm) {
            throw new BadApiRequestException('Пароли не совпадают');
        }

        if (mb_strlen($passNew) < 10) {
            throw new BadApiRequestException('Пароль должен содержать не менее 10 символов');
        }

        $user = Auth::user();
        if (!Hash::check($passOld, $user->password)) {
            throw new BadApiRequestException('Неверный пароль');
        }
        $user->password = Hash::make($passNew);
        $user->save();

        return response('');
    }

    public function getTwoFaData(\Illuminate\Http\Request $request) {
        $token = $request->get('token');
        $googleAuth = new GoogleAuthenticator();
        $secret = $googleAuth->generateSecret();
        $qr = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate(Auth::user()->email, $secret, getenv('APP_NAME'));
        return response()->json([
            'qrCodeUrl' => $qr,
            'secret' => $secret
        ]);
    }

    public function verifyTwoFaCode(\Illuminate\Http\Request $request) {
        $token = $request->get('token');
        $secret = $request->get('secret');
        $code = $request->get('code');
        $googleAuth = new GoogleAuthenticator();

        if (!$googleAuth->checkCode($secret, $code)) {
            throw new \InvalidArgumentException('Неверный код подтверждения');
        }

        $userSettings = UsersSettings::find(Auth::user()->id);
        $userSettings->secret_two_fa = $secret;
        $userSettings->save();

        return response('');
    }

}
