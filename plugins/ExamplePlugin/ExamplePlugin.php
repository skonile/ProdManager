<?php
declare(strict_types = 1);

namespace Plugins\ExamplePlugin;

use App\Database;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Products;
use App\Plugins\BasePlugin;

class ExamplePlugin extends BasePlugin{
    protected string $name = "Example Plugin";
    protected string $description = "";
    protected string|array|null $author = "Siyabonga Konile";
    protected string $systemName = 'ExamplePlugin';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addProduct(Product $product): bool{
        return true;
    }

    public function addProducts(Products $products): bool{
        return true;
    }

    public function updateProduct(Product $product): bool{
        return true;
    }

    public function deleteProduct(int $productId): bool{
        return true;
    }

    public function addProductCategory(Category $category): bool{
        return true;
    }

    public function updateProductCategory(Category $category): bool{
        return true;
    }

    public function deleteProductCategory(Category $category): bool{
        return true;
    }

    public function addProductTag(Tag $tag): bool{
        return true;
    }

    public function updateProductTag(Tag $tag): bool{
        return true;
    }

    public function deleteProductTag(Tag $tag): bool{
        return true;
    }

    public function addProductBrand(Brand $brand): bool{
        return true;
    }

    public function updateProductBrand(Brand $brand): bool{
        return true;
    }

    public function deleteProductBrand(Brand $brand): bool{
        return true;
    }
}