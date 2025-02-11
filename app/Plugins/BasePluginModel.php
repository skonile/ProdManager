<?php 
declare(strict_types=1);

namespace App\Plugins;

use App\Database;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;

abstract class BasePluginModel{
    protected Product|Tag|Category|Brand $model;

    public function __construct(Product|Tag|Category|Brand $model){
        $this->model = $model;
    }

    /**
     * Transfer method calls to undefined functions to the model object.
     * 
     * @param string $method The called method name
     * @param array $args The passed method arguments
     */
    public function __call(string $method, array $args){
        return $this->model->{$method}(...$args);
    }

    /**
     * Get the database connection.
     * 
     * @return Database The database connection object
     */
    public static function getDatabase(): Database{
        return Database::getInstance();
    }
}