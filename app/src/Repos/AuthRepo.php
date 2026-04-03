<?php

namespace App\Repos;

use App\Repos\Interfaces\IAuthRepo;
use App\Core\Repository;
use App\Models\UserModel;
use PDO;

class AuthRepo extends Repository implements IAuthRepo
{
    public function findByEmail(string $email): ?UserModel{
        $sql = "SELECT id, username, email, password_hash, role from users where email = :email";
        $fetchUser = $this->getConnection()->prepare($sql);
        $fetchUser->execute(['email' => $email]);
        $user = $fetchUser->fetch(PDO::FETCH_ASSOC);
        
        if(!$user){
            return null;
        }

        return new UserModel(
            $user['id'],
            $user['username'],
            $user['email'],
            $user['password_hash'],
            $user['role']
        );
    }

    public function createUser(UserModel $user): int {
        $hashedPassword = password_hash($user->getPasswordHash(), PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users (name, email, password_hash, role) 
                VALUES (:name, :email, :password, :role)";
        $stmt = $this->getConnection()->prepare($sql);
        
        return $stmt->execute([
            ':name' => $user->name,
            ':email' => $user->email,
            ':password' => $hashedPassword,
            ':role' => 'player' 
        ]);
    }
}