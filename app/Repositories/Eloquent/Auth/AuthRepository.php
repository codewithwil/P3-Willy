<?php

namespace App\Repositories\Eloquent\Auth;

use App\{
    Models\User,
    Repositories\BaseRepositories,
    Repositories\Contracts\Auth\AuthRepositoryContract
};

use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepositories implements AuthRepositoryContract{
    public function __construct(User $user)
    {
        parent::__construct($user);    
    }

    public function register(array $data): object
    {
        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        $user = $this->create($data);
        $user->assignRole('user');
        return $user;
    }

    public function login(string $email, string $password): array{
        $user = $this->findBy('email', $email);
        if ($user && Hash::check($password, $user->password)) {
            $roles = $user->getRoleNames();
            return [
                'success' => true,
                'message' => 'Login successful',
                'data' => $user,
                'roles' => $roles,
            ];
        }
 
        return [
            'success' => false,
            'message' => 'Invalid credentials',
        ];
    }

    public function logout(): void
    {
        auth('web')->logout();
    }
}