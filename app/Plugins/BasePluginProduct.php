<?php 
declare(strict_types = 1);

namespace App\Plugins;

/**
 * The buldin Product's methods
 * 
 * @method int|null getId()
 * @method void setId(?int $id)
 * @method string getName()
 * @method void setName(string $name)
 * @method string getCode()
 * @method void setCode(string)
 * @method string getDecription()
 * @method void setDescription(string $desciption)
 * @method float getPrice()
 * @method void setPrice(float $price)
 * @method Condition getCondition()
 * @method void setCondition(Condition $condition)
 * @method Brand|null getBrand()
 * @method void setBrand(Brand|null $brand)
 * @method array getCategories()
 * @method void setCategories(Category ...$categories)
 * @method void addCategories(Category ...$categories)
 * @method void addCategory(Category $category)
 * @method void removeCategory(Category $category)
 * @method array getTags()
 * @method void setTags(Tag ...$tags)
 * @method void addTags(Tag ...$tags)
 * @method void addTag(Tag $tag)
 * @method bool getIsInStock()
 * @method bool getIsPublished()
 * @method void setIsPublished()
 * @method array getImages()
 * @method void clearImages()
 * @method void setImages(array $images)
 * @method void addImage()
 * @method void addImages(array $images)
 * @method void removeImage(string $image)
 * @method void removeImages(array $images)
 * @method void removeImageAt(int $index)
 * @method int getQuantity()
 * @method void setQuantity(int $quantity)
 * @method Shipping getShipping()
 * @method void setShipping(Shipping $shipping)
 * @method string getClass()
 */
class BasePluginProduct extends BasePluginModel{}