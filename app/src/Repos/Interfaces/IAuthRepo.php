<?php 

namespace App\Repos\Interfaces;

interface IAuthRepo 
{
    public function findByEmail(string $email);
    public function createUser(string $name, string $email, string $password, int $roleId);
}