<?php

namespace App\Repositories\Contracts\Auth;

interface AuthRepositoryContract{
    public function register(array $data): object;
    public function login(string $email, string $password): array;
    public function logout(): void;
}