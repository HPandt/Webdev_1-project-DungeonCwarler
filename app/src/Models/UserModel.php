<?php

namespace App\Models;
use App\Core\Repository;
use App\Models\Enums\Roles;

class UserModel{
    public int $id;
    public string $name;
    public string $email;
    public string $password_hash;
    public Roles $role;
    public function __construct(
        int $id,
        string $name,
        string $email,
        string $password_hash,
        Roles $role
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->role = $role;
    }

    public static function fromArray(array $data): UserModel {
        $roleValue = $data['role'] ?? 'player';
        $password = $data['password'] ?? $data['password_hash'] ?? '';

        return new UserModel(
            id: isset($data['id']) ? (int)$data['id'] : 0,
            name: $data['username'] ?? 'Unknown',
            email: $data['email'] ?? 'Unknown',
            password_hash: $password,
            role: Roles::from((string)$roleValue)
        );
    }

    public function getPasswordHash(): string {
        return $this->password_hash;
    }

}


