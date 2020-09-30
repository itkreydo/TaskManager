<?php


namespace App\Services\KeyService;


interface KeyStoreInterface
{

    public static function getKeys() : Keys;

    public static function saveKeys(Keys $keys);
}
