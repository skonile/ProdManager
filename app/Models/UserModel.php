<?php 
declare(strict_types = 1);

namespace App\Models;

use App\Crypto;
use DateTime;
use App\Models\User;

class UserModel extends BaseModel{
    public function addUser(
        string $name, 
        string $lastname, 
        string $username, 
        string $email, 
        string $password, 
        UserLevel $level): bool{
            $password = Crypto::encrypt($password);
            $sql = "INSERT INTO user (first_name, last_name, username, email, password, level) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            return $this->database->preparedQueryWithBool(
                $sql,
                [
                    $name,
                    $lastname,
                    $username,
                    $email,
                    $password,
                    $level->value
                ]
            );
    }

    public function getUser(int $userId): ?User{
        $sql = "SELECT * FROM user WHERE id = ?";
        $res = $this->database->preparedQuery($sql, [$userId]);
        if($res->num_rows != 1)
            return null;

        $row = $res->fetch_assoc();
        return $this->convertDBUserToObj($row);
    }

    /**
     * Get all the added users.
     *
     * @return array<User>
     */
    public function getAllUsers(): array{
        $sql = "SELECT * FROM user";
        $res = $this->database->query($sql);

        if($res->num_rows < 1){
            return [];
        }

        $users = [];
        while($row = $res->fetch_assoc()){
            $users[] = $this->convertDBUserToObj($row);
        }
        return $users;
    }

    public function getUserByUsername(string $username): ?User{
        $sql = "SELECT * FROM user WHERE username = ?";
        $res = $this->database->preparedQuery($sql, [$username]);
        if($res->num_rows != 1)
            return null;

        $row = $res->fetch_assoc();
        return $this->convertDBUserToObj($row);
    }

    protected function convertDBUserToObj($row): User{
        return new User(
            id: (int) $row['id'], 
            name: $row['first_name'],
            lastname: $row['last_name'],
            username: $row['username'],
            email: $row['email'],
            password: $row['password'],
            level: UserLevel::fromString($row['level']),
            createdBy: new DateTime($row['created_by']),
            modifiedBy: new DateTime($row['modified_by'])
        );
    }

    public function updateUser(
        int $id,
        string $name, 
        string $lastname, 
        string $username, 
        string $email, 
        string $password, 
        UserLevel $level): bool{
            $sql = "UPDATE user SET 
                first_name = '{$name}', 
                last_name = '{$lastname}', 
                username = '{$username}', 
                email = '{$email}', 
                password = '{$password}', 
                level = '{$level->value}', 
                modified_by = CURRENT_TIMESTAMP 
                WHERE id = {$id}";
            return $this->database->queryWithBool($sql);
    }

    public function removeUser(int $id): bool{
        $sql = "DELETE FROM user WHERE id = {$id}";
        return $this->database->queryWithBool($sql);
    }
}