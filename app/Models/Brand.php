<?php
declare(strict_types = 1);

namespace App\Models;

use App\Exceptions\ModelExceptions\EmptyBrandNameException;
use App\Exceptions\ModelExceptions\IdLessThanOneException;

class Brand{
    private ?int $id;

    private string $name;

    /**
     * Initialize properties.
     * 
     * @param int|null $id The brand id
     * @param string $name The brand name
     */
    public function __construct(?int $id, string $name){
        $this->setId($id);
        $this->setName($name);
    }

    public function getId(): ?int{
        return $this->id;
    }

    public function setId(?int $id): void{
        if($id !== null && $id <= 0)
            throw new IdLessThanOneException();
        $this->id = $id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function setName(string $name): void{
        $name = trim($name);
        if(strlen($name) == 0){
            throw new EmptyBrandNameException();
        }
        $this->name = $name;
    }

    /**
     * Get the fully qualified name of the current class.
     * 
     * @param string The class name
     */
    static public function getClass(): string{
        return __CLASS__;
    }
}