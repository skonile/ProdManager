<?php
declare(strict_types = 1);

namespace App\Models;

use App\Database;

class BaseModel{
    /**
     * Active database connection.
     */
    protected Database $database;

    /**
     * Initialize the database object.
     */
    public function __construct(Database $db){
        $this->database = $db;
    }

    protected function calculateOffsetNumFromPageLimit(int $page, int $limit): int{
        return ($limit * $page) - $limit;
    }
}