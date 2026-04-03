<?php

namespace App\Services\Interfaces;

use App\Models\UserModel;

interface IAuthService {
    
    public function login( string $email, string $password);
    public function createUser(UserModel $user): bool;
}