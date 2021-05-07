<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\CommonResponses;
use App\Models\User;
use JWTAuth;

class AuthenticatorController extends Controller
{
    use CommonResponses;
    
    public function login(Request $request)
    {
        $validator = $this->validator($request, [
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string',
        ]);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        }

        if (!$token = JWTAuth::attempt($validator->content)) {
            return $this->sendJsonResponse([
                'errors' => [ 'password' => 'Password mismatch' ],
            ], 400);
        }

        return $this->sendJsonResponse([
            'token' => $token,
            'user' => auth()->user(),
        ]);
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::parseToken()->invalidate();
        } catch (\Exception $e) {
            return $this->successMessage('Logged out from an invalid token');
        } 

        return $this->successMessage('Logged out successfully');
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request, [
            'username' => 'required|string|max:255|regex:/^\S*$/u|unique:users,name',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if (!$validator->is_valid) {
            return $this->sendJsonResponse($validator->content, 400);
        }

        $user = User::create([
            'name' => $request->get('username'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->sendJsonResponse(compact('token', 'user'), 201);
    }
}
