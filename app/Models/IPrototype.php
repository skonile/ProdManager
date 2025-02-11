<?php
declare(strict_types = 1);

namespace App\Models;

/**
 * Interface for the prototype design pattern.
 */
interface IPrototype{
    public function clone();
}