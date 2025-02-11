<?php 
declare(strict_types = 1);

namespace App\Models;

use DateTime;

class User{
    protected int $id;

    protected string $name;

    protected string $lastname;

    protected string $username;

    protected string $email;

    protected string $password;

    /** @var UserLevel $level The access position of the user to the software */
    protected UserLevel $level;

    protected DateTime $createdBy;

    protected DateTime $modifiedBy;

    public function __construct(
        int $id, 
        string $name, 
        string $lastname, 
        string $username, 
        string $email, 
        string $password,
        UserLevel $level,
        DateTime $createdBy, 
        DateTime $modifiedBy){
            $this->id = $id;
            $this->name = $name;
            $this->lastname = $lastname;
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
            $this->level = $level;
            $this->createdBy = $createdBy;
            $this->modifiedBy = $modifiedBy;
        }

    public function getId(): int{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }
    
    public function getLastname(): string{
        return $this->lastname;
    }

    public function getUsername(): string{
        return $this->username;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getLevel(): UserLevel{
        return $this->level;
    }

    public function getCreatedBy(): DateTime{
        return $this->createdBy;
    }

    public function getModifiedBy(): DateTime{
        return $this->modifiedBy;
    }
}