<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\AuthenticationRepository;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationRepository $authenticationRepository,
    ) {
    }

    public function login(AuthenticateRequest $request)
    {
        $credentials = $request->validated();

        $user = $this->authenticationRepository->findUserByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(null, 401);
        }

        $token = $this->authenticationRepository->createToken($user);

        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(null);
    }
}
