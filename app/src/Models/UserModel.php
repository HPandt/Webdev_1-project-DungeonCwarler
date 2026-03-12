<?php

namespace App\Models;
use App\Core\Repository; 
class UserModel{
    public int $id;
    public string $name;
    public string $email;
    public string $password_hash;
    public string $role;
    public function __construct(
        int $id,
        string $name,
        string $email,
        string $password_hash,
        string $role
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->role = $role;
    }

    public function getPasswordHash(): string {
        return $this->password_hash;
    }

}


