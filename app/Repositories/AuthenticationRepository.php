<?php

namespace App\Repositories;

use App\Models\User;

class AuthenticationRepository
{
    public function findUserByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    public function createToken(User $user): string
    {
        return $user->createToken($user->name . '-AuthToken')->plainTextToken;
    }
}
