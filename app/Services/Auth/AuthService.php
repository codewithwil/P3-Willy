<?php

namespace App\Services\Auth;

use App\{
    Repositories\Contracts\Auth\AuthRepositoryContract,
};
use App\Dto\Auth\LoginDto;
use Illuminate\{
    Support\Facades\Validator,
};
use Illuminate\Support\Facades\Log;

class AuthService{
    public function __construct(protected AuthRepositoryContract $authRepo){}

    public function login(LoginDto $dto): array {
        $credentials = ['email' => $dto->email, 'password' => $dto->password];
    
        $validator = Validator::make($credentials, [
            'email'    => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }
    
        return $this->authRepo->login($dto->email, $dto->password);
    }
    

    public function register(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => $validator->errors()->first(),
            ];
        }
    
        $user = $this->authRepo->register($data);
    
        return [
            'success' => true,
            'message' => 'User registered successfully.',
            'data' => $user,
        ];
    }
    

    public function logout(): void{
        $this->authRepo->logout();
    }
}