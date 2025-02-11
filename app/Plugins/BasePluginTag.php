<?php
declare(strict_types=1);

namespace App\Plugins;

/**
 * the buldin Tag's methods
 * 
 * @method int|null getId()
 * @method void setId(int $id)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getSlug()
 * @method void setSlug(string $slug)
 * @method bool getIsPublished()
 * @method void setIsPublished()
 * @method @static string getClass()
 * @method Tag clone()
 */
class BasePluginTag extends BasePluginModel{}