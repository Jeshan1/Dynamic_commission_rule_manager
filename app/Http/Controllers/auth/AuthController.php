<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    
    public function login(UserRequest $request)
    {
        try {

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse('The provided credentials are incorrect.', 401);
            }

            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('api-token')->plainTextToken;

            $data = ['token' => $token,
                     'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'role' => $user->role
                        ]
                    ];

            return $this->successResponse('Successfully logged in.', $data, 200);

        } catch (\Exception $e) {

            Log::error('Login error: ' . $e->getMessage());

            return $this->errorResponse('An error occurred during login. Please try again.', 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse('Successfully logged out.',200);
    }
}
