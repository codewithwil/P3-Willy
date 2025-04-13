<?php

namespace App\Dto\Auth;

class RegisterDto{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $password_confirmation,
    ) {}

    public static function fromRequest($request): self{
        return new self(
            name:                  $request->input('name'),
            email:                 $request->input('email'),
            password:              $request->input('password'),
            password_confirmation: $request->input('password_confirmation'),
        );
    }
}