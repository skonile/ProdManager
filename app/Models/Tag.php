<?php
declare(strict_types = 1);

namespace App\Models;

use App\Exceptions\ModelExceptions\EmptyTagNameException;
use App\Exceptions\ModelExceptions\EmptyTagSlugException;

class Tag implements IPrototype{
    /**
     * 
     * The database generated id for the tag.
     */
    private ?int $id;

    /**
     * The tag name.
     */
    private string $name;

    /**
     * An alphanumeric identifier for the resource unique to its type.
     */
    private string $slug;

    /**
     * An identifier of a tag if is published or not.
     */
    private bool $isPublished;

    public function __construct(
        ?int $id,
        string $name,
        string $slug,
        bool $isPublished = false
    ){
        $this->setId($id);
        $this->setName($name);
        $this->setSlug($slug);
        $this->setIsPublished($isPublished);
    }

    public function getId(): int|null{
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
    }

    public function getName(): string{
        return $this->name;
    }

    /**
     * Set the tag name.
     * 
     * @param string $name The tag name
     * @throws EmptyTagNameException
     */
    public function setName(string $name){
        $name = trim($name);
        if(\strlen($name) == 0)
            throw new EmptyTagNameException();
        $this->name = $name;
    }

    public function getSlug(): string{
        return $this->slug;
    }

    /**
     * Set the tag slug.
     * 
     * @param string $slug The tag slug
     * @throws EmptyTagSlugException
     */
    public function setSlug(string $slug){
        $slug = trim($slug);
        if(strlen($slug) == 0)
            throw new EmptyTagSlugException();
        $this->slug = $slug;
    }

    /**
     * Get the tag status.
     * 
     * @return bool Returns true if the tag is published 
     *              or false otherwise
     */
    public function getIsPublished(): bool{
        return $this->isPublished;
    }

    /**
     * set the tag status.
     * 
     * @param bool $status The new value of the status.
     */
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
     * @return Tag The copy of the current object
     */
    public function clone(){
        $newTag = new Tag(
            $this->id,
            $this->name,
            $this->slug
        );
        return $newTag;
    }
}