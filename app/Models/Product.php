<?php
declare(strict_types = 1);

namespace App\Models;

use App\Functions;
use App\Models\Brand;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Tag;

use App\Exceptions\ModelExceptions\IdLessThanOneException;
use App\Exceptions\ModelExceptions\EmptyProductNameException;
use App\Exceptions\ModelExceptions\EmptyProductDescriptionException;
use App\Exceptions\ModelExceptions\NegativeProductPriceException;
use App\Exceptions\ModelExceptions\InvalidProductImageURLException;
use App\Exceptions\ModelExceptions\ProductQuantityLessThanZeroException;

class Product{
    #region Product's properties
    /**
     * Database Id given to the product.
     */
    protected ?int $id;

    /**
     * Name of the product.
     */
    protected string $name = "";

    /**
     * The product's production code if it has one.
     */
    protected ?string $code;

    /**
     * The products description.
     */
    protected string $description = "";

    /**
     * The product's current price.
     */
    protected float $price;

    /**
     * The product's condition.
     */
    protected Condition $condition;

    /**
     * The brand that made the product.
     */
    protected ?Brand $brand;

    /**
     * Categories the product can be put in.
     * 
     * @property array<Tag> $categories
     */
    protected array $categories;

    /**
     * Tags that the product can be put in.
     * 
     * @property array<Tag> $tags
     */
    protected array $tags;

    /**
     * Tracks if the product is in stock or not and 
     * that is determined by the product quantity.
     */
    protected bool $isInstock;

    /**
     * Tracks if the product is published or saved as a draft.
     */
    protected bool $isPublished;

    /**
     * An array containing a list of image urls to the the product's images.
     * 
     * @property array<string> $images
     */
    protected array $images;

    /**
     * The number of units that is available to be sold at any moment.
     */
    protected int $quantity;

    /**
     * Details about shipping fees for local, nationwide and 
     * international buyers.
     */
    protected Shipping $shipping;
    #endregion

    #region Product's constructor
    /**
     * Initialize all the properties of the object.
     */
    public function __construct(
        ?int $id,
        string $name,
        ?string $code,
        string $description,
        float $price,
        ?Brand $brand = null,
        array $categories = [],
        array $tags = [],
        bool $isPublished = false,
        array $images = [],
        int $quantity = 0,
        Shipping $shipping = new Shipping(),
        Condition $condition = Condition::New
    ){
        $this->setId($id);
        $this->setName($name);
        $this->setCode($code);
        $this->setDescription($description);
        $this->setPrice($price);
        $this->setBrand($brand);
        $this->setCategories(...$categories);
        $this->setTags(...$tags);
        $this->setIsPublished($isPublished);
        $this->setImages($images);
        $this->setQuantity($quantity);
        $this->setShipping($shipping);
        $this->setCondition($condition);
    }
    #endregion

    #region Product's methods
    /**
     * Get the Database id for the product.
     * 
     * The id is given by the database when the product is 
     * save to it and if the product does hasn't been 
     * saved yet then it will have no id.
     * 
     * @return ?int the database id if it axist
     */
    public function getId(): ?int{
        return $this->id;
    }

    /**
     * Set the id of the product.
     * 
     * @param ?int $id The id to set
     * @throws IdLessThanOneException
     */
    public function setId(?int $id): void{
        if($id !== null && $id <= 0)
            throw new IdLessThanOneException();
        $this->id = $id;
    }

    /**
     * Get the name of the product.
     * 
     * @return string name of the product
     */
    public function getName(): string{
        return $this->name;
    }

    /**
     * Set the name of the product.
     * 
     * The name of the product can not be an empty string or 
     * spaces of any size.
     * 
     * @param string $name The name of the product
     * @throws EmptyProductNameException 
     */
    public function setName($name){
        if(Functions\isStrEmpty($name)){
            throw new EmptyProductNameException();
        }
        $this->name = $name;
    }

    /**
     * Get the product code if it exist.
     * 
     * @return string Product code
     */
    public function getCode(): string{
        if($this->code == null){
            return '';
        }
        return $this->code;
    }

    /**
     * Set the product code.
     * 
     * If the given product code is an empty string or 
     * spaces then it is set to null.
     * 
     * @param string $code The product's code
     */
    public function setCode(string $code){
        $code = trim($code);
        if(strlen($code) == 0){
            $this->code = null;
            return;
        }
        $this->code = $code;
    }

    /**
     * Get the product's description.
     * 
     * @return string Product's description
     */
    public function getDescription(): string{
        return $this->description;
    }

    /**
     * Set the product's description.
     * 
     * @param string Product's description
     * @throws EmptyProductDescriptionException When the description is 
     *                                          an empty string or spaces
     */
    public function setDescription(string $description){
        $description = trim($description);
        if(strlen($description) == 0){
            throw new EmptyProductDescriptionException();
        }
        $this->description = $description;
    }

    /**
     * Get the product's price.
     * 
     * @return float The product price
     */
    public function getPrice(): float{
        return $this->price;
    }

    /**
     * Set the product's price.
     * 
     * @param float $price The given product price
     * @throws NegativeProductPriceException If the price is negative
     */
    public function setPrice(float $price){
        if($price < 0.0){
            throw new NegativeProductPriceException();
        }
        $this->price = $price;
    }

    /**
     * Get the product's condition.
     * 
     * @return Condition
     */
    public function getCondition(): Condition{
        return $this->condition;
    }

    /**
     * Set the product's condition.
     * 
     * @param Condition $condition
     */
    public function setCondition(Condition $condition){
        $this->condition = $condition;
    }

    /**
     * Get the brand of the product.
     * 
     * @return ?Brand Product brand
     */
    public function getBrand(): ?Brand{
        return $this->brand;
    }

    /**
     * Set the brand of the product.
     * 
     * @param ?Brand $brand Product brand
     */
    public function setBrand(?Brand $brand){
        $this->brand = $brand;
    }

    /**
     * Get categories that the product can be put in.
     * 
     * @return array categories
     */
    public function getCategories(): array{
        return $this->categories;
    }

    /**
     * Set the categories to the given categories
     * 
     * This method removes any categories that were set.
     * The method also removes any thing that is 
     * not an instance of [Category] in the given array.
     * 
     * @param array $categories
     */
    public function setCategories(Category ...$categories){
        $this->categories = [];
        $this->addCategories(...$categories);
    }

    /**
     * Add given categories to the current ones if any.
     * 
     * @param array $categories The categories to be added
     */
    public function addCategories(Category ...$categories){
        foreach($categories as $category){
            $this->addCategory($category);
        }
    }

    /**
     * Add a single category to the already existing cateories.
     * 
     * @param Category $category The category to be added
     */
    public function addCategory(Category $category){
        if(\in_array($category, $this->categories)) return;
        $this->categories[] = $category;
    }

    /**
     * Remove a given category from the array of categories.
     * 
     * @param Category $category The category to be removed
     * @return Category|null Return the removed category and
     *                      if the category does not exist
     *                      null is returned
     */
    public function removeCategory(Category $category){
        if(!\in_array($category, $this->categories)) 
            return null;

        // Find the index of the element, 
        // remove it and the re-index the array.
        $index = \array_search($category, $this->categories);
        unset($this->categories[$index]);
        $this->categories = \array_values($this->categories);
        return $category;
    }

    /**
     * Get tags that the products can be accossiated with.
     * 
     * @return array The tags for the product
     */
    public function getTags(): array{
        return $this->tags;
    }

    /**
     * Set tags for the product
     * 
     * This method clears tags before setting the new tags.
     * 
     * @param array $tags The tags to set
     */
    public function setTags(Tag ...$tags){
        $this->tags = [];
        $this->addTags(...$tags);
    }

    /**
     * Add given tags to the current tags.
     * 
     * @param array $tags The tags to be added
     */
    public function addTags(Tag ...$tags){
        foreach($tags as $tag){
            $this->addTag($tag);
        }
    }

    /**
     * Add a tag to the current tags
     * 
     * @param Tag $tag The tag to be added
     */
    public function addTag(Tag $tag){
        if(\in_array($tag, $this->tags)) return;
        $this->tags = \array_merge($this->tags, [$tag]);
    }

    /**
     * Get if the product is in stock or not.
     * 
     * This menthod uses the quantity property to determine 
     * if the product is in stock or not.
     * 
     * @return bool
     */
    public function getIsInstock(): bool{
        if($this->quantity > 0) return true;
        return false;
    }

    /**
     * Get if the product is published or is still in draft mode.
     * 
     * @return bool True if the product is pyublished 
     *              or false otherwise
     */
    public function getIsPublished(): bool{
        return $this->isPublished;
    }

    /**
     * Set product mode to published or not.
     * 
     * If the product is not piblished the it is in draft mode.
     * 
     * @param bool $isPublished Is published value
     */
    public function setIsPublished(bool $isPublished){
        $this->isPublished = $isPublished;
    }

    /**
     * Get the product's images.
     * 
     * The product images are contained in an array and 
     * each element is a url to a remote image.
     * 
     * @return array The image urls
     */
    public function getImages(): array{
        return $this->images;
    }

    /**
     * Clears all the set images.
     */
    public function clearImages(): void{
        $this->images = [];
    }

    /**
     * Set the product's images.
     * 
     * This sets the product's images to new remote image urls.
     * 
     * @param array $images The array of image urls
     * @throws InvalidProductImageURLException 
     */
    public function setImages(array $images){
        $this->clearImages();
        $this->addImages($images);
    }

    /**
     * Add an image url to the product's images.
     * 
     * The image url is added only if it is valid 
     * otherwise throws an exception.
     * 
     * @param string $image The image to be added
     * @throws InvalidProductImageURLException 
     */
    public function addImage(string $image){
        // if(!Functions\isValidURL($image)){
        //     throw new InvalidProductImageURLException();
        // }
        if(\in_array($image, $this->images)) return;
        $this->images = \array_merge($this->images, [$image]);
    }

    /**
     * Add product's image urls to already existing ones.
     * 
     * @param array $images Image urls to be added
     * @throws InvalidProductImageURLException
     */
    public function addImages(array $images){
        $images = Functions\getArrayOfStrings($images);
        foreach($images as $image){
            $this->addImage($image);
        }
    }

    /**
     * Remove an image url from the product's image urls.
     * 
     * @param string $image The url to be removed
     */
    public function removeImage(string $image){
        if(!\in_array($image, $this->images)) return;
        $index = \array_search($image, $this->images);
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    /**
     * Remove more than one image url from the product's image urls.
     * 
     * @param array $images The image urls to be removed
     */
    public function removeImages(array $images){
        foreach($images as $image){
            if(!\is_string($image)) continue;
            $this->removeImage($image);
        }
    }

    /**
     * Remove an image url at a given index.
     * 
     * @param int $index The index to remove image at
     */
    public function removeImageAt(int $index){
        if(!\array_key_exists($index, $this->images)) return;
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    /**
     * Get the quantity of the product.
     * 
     * @return int The quantity
     */
    public function getQuantity(): int{
        return $this->quantity;
    }

    /**
     * Set the quantity of the product.
     * 
     * The quantity of the product cannot be less than zero
     * otherwise it throws an exception.
     * 
     * @param int $quantity The quantity to be set
     * @throws ProductQuantityLessThanZeroException
     */
    public function setQuantity(int $quantity){
        if($quantity < 0){
            throw new ProductQuantityLessThanZeroException();
        }

        $this->isInstock = ($quantity === 0)? false: true;
        $this->quantity = $quantity;
    }

    /**
     * Get the shipping object.
     * 
     * @return Shipping Shipping object
     */
    public function getShipping(): Shipping{
        return $this->shipping;
    }

    /**
     * Set the shipping object.
     * 
     * @param Shipping $shipping The shipping object to set
     */
    public function setShipping(Shipping $shipping){
        $this->shipping = $shipping;
    }

    /**
     * Get the fully qualified class name.
     * 
     * @return string The fully qualified class name
     */
    static public function getClass(): string{
        return __CLASS__;
    }
    #endregion
}
?>