<?php

declare(strict_types=1);
namespace App\Utils;

class PasswordUtil
{

    public static function encryptPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }


    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function hashJson(string $json, string $secretKey ) {
        $iv = openssl_random_pseudo_bytes(16);
        $cipher = 'aes-256-cbc';
        $encrypted = openssl_encrypt(json_encode($json), $cipher, hex2bin($secretKey), OPENSSL_RAW_DATA, $iv);
        return bin2hex($iv) . ':' . bin2hex($encrypted);
    }
}
