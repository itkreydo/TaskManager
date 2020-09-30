<?php


namespace App\Services\KeyService;


use Illuminate\Support\Facades\Storage;

class KeyStore implements KeyStoreInterface
{
    private $publicKey;
    private $privateKey;

    public static function getKeys() : Keys {
        $privateKey = Storage::get('private.key');
        $publicKey = Storage::get('public.key');
        return new Keys($publicKey, $privateKey);
    }

    public static function saveKeys(Keys $keys) {
        Storage::put('private.key', $keys->getPrivateKey());
        Storage::put('public.key', $keys->getPublicKey());
    }
}
