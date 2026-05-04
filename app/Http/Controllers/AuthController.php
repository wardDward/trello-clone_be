<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = $this->authService->register($validated);

        return response()->json([
            'message' => 'Account created successfully',
            'user' => new UserResource($user),
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $service = $this->authService->login($validated);

        return response()->json([
            'token' => $service['token'],
            'user' => new UserResource($service['user']),
        ]);
    }

    public function validate(){
        return Auth::user() ? new UserResource(Auth::user()) : null;
    }
}
