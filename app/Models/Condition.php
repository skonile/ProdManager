<?php
declare(strict_types = 1);

namespace App\Models;

enum Condition: string{
    case New = "new";
    case Good = "good";
    case Fair = "fair";
    case NotWorking = "notworking";

    public static function fromString(string $str): ?Condition{
        foreach(Condition::cases() as $case){
            if($str === $case->value) return $case;
        }

        return null;
    }
}