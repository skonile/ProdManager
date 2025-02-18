<?php 
declare(strict_types = 1);

namespace App\Models;

enum UserLevel: string{
    case Admin  = "admin";
    case User   = "user";
    case Viewer = "viewer";

    public static function fromString(string $str): ?UserLevel{
        foreach(UserLevel::cases() as $case){
            if($str === $case->value) return $case;
        }

        return null;
    }

    public static function all(): array{
        return [
            UserLevel::Viewer->value,
            UserLevel::User->value,
            UserLevel::Admin->value
        ];
    }
}