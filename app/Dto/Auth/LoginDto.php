<?php

namespace App\Dto\Auth;

class LoginDto{
    
    public function __construct(
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest($request): self{
        return new self(
            email:    $request->input('email'),
            password: $request->input('password'),
        );
    }
}