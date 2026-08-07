<?php

namespace App\Http\Controllers\Api;

use App\DTOs\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginDTO $dto): JsonResponse
    {
        $user = User::where('email', $dto->email)->first();

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken('api')->accessToken,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        request()->user()->token()->revoke();

        return response()->json(['message' => 'Logged out.']);
    }
}
