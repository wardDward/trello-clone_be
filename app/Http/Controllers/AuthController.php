<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService)
    {}

    public function register(RegisterRequest $request){
        $validated = $request->validated();
        $user = $this->authService->register($validated);
        return new UserResource($user);
    }

    public function login(LoginRequest $request){
        $validated = $request->validated();
        $service = $this->authService->login($validated);   
        return response()->json([
            'token' => $service['token'],
            'user' => new UserResource($service['user'])
        ]);
    }
}
