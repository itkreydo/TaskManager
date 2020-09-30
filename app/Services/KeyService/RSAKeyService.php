<?php


namespace App\Services\KeyService;


use App\Services\KeyService\KeyServiceInterface;

class RSAKeyService implements KeyServiceInterface
{

    public function generate($bits) : Keys
    {
        $key = openssl_pkey_new([
            'digest_alg' => 'sha256',
            'private_key_bits' => $bits,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($key, $privateKey);
        $pubKey = openssl_pkey_get_details($key)['key'];

        return new Keys($pubKey, $privateKey);
    }

}
