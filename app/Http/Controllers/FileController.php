<?php


namespace App\Http\Controllers;

use App\Services\KeyService\KeyStore;
use App\Services\KeyService\RSAKeyService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function avatars(Request $request, $fileName)
    {

        if (!Storage::exists("private/avatars/{$fileName}")) {
            return response('',404);
        }
//        dd("private/avatars/{$fileName}", $file);
        return Storage::response("private/avatars/{$fileName}");
    }

    public function key(Request $request)
    {

       $key = new RSAKeyService();
       $keys = $key->generate(4096);

       KeyStore::saveKeys($keys);
       var_dump(KeyStore::getKeys());

       return response('');
    }

}
