<?php
declare(strict_types = 1);

namespace App;

class Crypto{
    public static function encrypt(string $string): string{
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function verify(string $string, string $hash): bool{
        return password_verify($string, $hash);
    }
}