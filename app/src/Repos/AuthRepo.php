<?php

namespace App\Repos;

use App\Repos\Interfaces\IAuthRepo;
use App\Core\Repository;
use App\Models\UserModel;
use App\Models\Enums\Roles;
use PDO;
use PDOException;

class AuthRepo extends Repository implements IAuthRepo
{
    public function findByEmail(string $email): ?UserModel
    {
        try {
            $sql = "SELECT id, username, email, password_hash, role from users where email = :email";
            $fetchUser = $this->getConnection()->prepare($sql);
            $fetchUser->execute(['email' => $email]);
            $user = $fetchUser->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return null;
            }

            return new UserModel(
                $user['id'],
                $user['username'],
                $user['email'],
                $user['password_hash'],
                Roles::from((string)$user['role'])
            );
        } catch (PDOException $th) {
            throw new PDOException("Error creating user: " . $th->getMessage());
        }
    }

    public function createUser(UserModel $user): int
    {
        try {
            $hashedPassword = password_hash($user->getPasswordHash(), PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password_hash, role) 
                    VALUES (:name, :email, :password, :role)";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':name', $user->name);
            $stmt->bindValue(':email', $user->email);
            $stmt->bindValue(':password', $hashedPassword);
            $stmt->bindValue(':role', $user->role->value);
            return $stmt->execute();
        } catch (PDOException $th) {
            throw new PDOException("Error creating user: " . $th->getMessage());
        }
    }
}
