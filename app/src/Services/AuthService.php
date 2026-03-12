<?php 

namespace App\Services;

use App\Services\Interfaces\IAuthService;
use App\Repos\Interfaces\IAuthRepo;
use App\Models\UserModel;

class AuthService implements IAuthService {
    private IAuthRepo $authRepository;

    public function __construct(IAuthRepo $authRepository) {
        $this->authRepository = $authRepository;
        
    }
    // Implementation of auth service methods

    public function login(string $email, string $password): ?UserModel
    {
        // 1. Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        // 2. Fetch user
        $user = $this->authRepository->findByEmail($email);

        if (!$user) {
            return null;
        }

        // 3. Verify password
        if (!password_verify($password, $user->getPasswordHash())) {
            return null;
        }

        // 4. Success
        return $user;
    }
    public function createUser($name, $email, $password, $roleId){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new \InvalidArgumentException("Invalid email");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    return $this->authRepository->createUser(
        $name,
        $email,
        $hashedPassword,
        $roleId
    );
    }
    
}