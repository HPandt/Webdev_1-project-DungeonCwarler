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

    public function createUser(string $username, string $email, string $password, int $roleId=2) {
        $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :password, :role)";
        $createUser = $this->getConnection()->prepare($sql);
        $createUser->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $roleId
        ]);
        return $this->getConnection()->lastInsertId();
    }
}