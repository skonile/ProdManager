<?php 
declare(strict_types=1);

namespace App\Plugins;

use App\Models\Category;

/**
 * the buldin Category's methods
 * 
 * @method int|null getId()
 * @method void setId(int $id)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getSlug()
 * @method void setSlug(string $slug)
 * @method int|null getParentId()
 * @method Category|null getParentObject(CategoryModel|null $categoryModel = null)
 * @method void setParentId(int|null $parentCatId)
 * @method bool getIsPublished()
 * @method void setIsPublished(bool $isPublished)
 * @method @static string getClass()
 * @method Category clone()
 */
class BasePluginCategory extends BasePluginModel{}