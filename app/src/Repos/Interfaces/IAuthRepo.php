<?php 

namespace App\Repos\Interfaces;

use App\Models\UserModel;

interface IAuthRepo 
{
    public function findByEmail(string $email);
    public function createUser(UserModel $user);
}