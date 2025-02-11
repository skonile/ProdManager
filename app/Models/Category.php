<?php
declare(strict_types = 1);

namespace App\Models;

use App\Database;
use App\Exceptions\ModelExceptions\EmptyCategorySlugExcption;
use App\Exceptions\ModelExceptions\EmptyCategoryNameException;

class Category implements IPrototype{
    /**
     * @var int|null $id Category id.
     */
    private ?int $id;

    /**
     * @var string $name Category name.
     */
    private string $name;

    /**
     * @var string $slug An alphanumeric identifier for the resource unique to its type.
     */
    private string $slug;

    /**
     * @var int|null $parent The ID for the parent of the resource.
     */
    private ?int $parent;

    /**
     * @var bool $isPublished
     */
    private bool $isPublished;

    public function __construct(
        ?int $id,
        string $name, 
        ?string $slug = null,
        ?int $parent = null,
        bool $isPublished = false){
            $this->id = $id;
            $this->setName($name);
            $this->setSlug($slug);
            $this->setParentId($parent);
            $this->setIsPublished($isPublished);
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    /**
     * Get category name.
     *
     * @return string
     */
    public function getName(): string{
        return $this->name;
    }

    /**
     * Set the category name.
     *
     * @throws EmptyCategoryNameException When the given name is empty.
     * 
     * @param string $name
     * @return void
     */
    public function setName(string $name){
        $name = trim($name);
        if(\strlen($name) == 0)
            throw new EmptyCategoryNameException();
        $this->name = $name;
    }

    /**
     * Get category slug.
     * 
     * @return string
     */
    public function getSlug(): string{
        return $this->slug;
    }

    /**
     * Set the category slug.
     * 
     * @param string $slug The slug
     * @throws EmptyCategorySlugExcption
     */
    public function setSlug(string $slug){
        $slug = trim($slug);
        if(\strlen($slug) == 0)
            throw new EmptyCategorySlugExcption();
        $this->slug = $slug;
    }

    /**
     * Get the parent category id.
     * 
     * @return string|null The parent id
     */
    public function getParentId(): ?int{
        return $this->parent;
    }

    /**
     * Get the categry objet of the parent using its id.
     * 
     * @param CategoryModel|null $categoryModel
     * @return ?Category
     */
    public function getParentObject(?CategoryModel $categoryModel = null): ?Category{
        if($categoryModel == null)
            $categoryModel = new CategoryModel(Database::getInstance());

        if($this->parent == null) 
            return null;
        $parent = $categoryModel->getCategory($this->parent);

        if($parent === false) 
            return null;
        return $parent;
    }

    /**
     * Set the parent category of this category.
     * 
     * @param ?int $parentCatId The parent category
     */
    public function setParentId(?int $parentCatId){
        $this->parent = $parentCatId;
    }

    public function getIsPublished(): bool{
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished){
        $this->isPublished = $isPublished;
    }

    /**
     * Get the fully qualified class name.
     * 
     * @return string The fully qualified class name
     */
    static public function getClass(): string{
        return __CLASS__;
    }

    /**
     * Create a duplicate of the object.
     * 
     * @return Category The copy of the current object
     */
    public function clone(){
        $newCategory = new Category(
            $this->id,
            $this->name,
            $this->slug,
            $this->parent,
            $this->isPublished
        );
        return $newCategory;
    }
}