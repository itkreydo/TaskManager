<?php


namespace App\Services\KeyService;


class Keys
{
    private $publicKey;
    private $privateKey;
    public function __construct($publicKey, $privateKey)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function setPublicKey($key) {
        $this->publicKey = $key;
    }

    public function setPrivateKey($key) {
        $this->privateKey = $key;
    }
}
