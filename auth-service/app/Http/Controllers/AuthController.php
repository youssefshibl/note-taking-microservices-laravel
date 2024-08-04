<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {

        try {
            $user = User::create($request->only('name', 'email') + [
                'password' => Hash::make($request->input('password')),
            ]);

            $token = auth('api')->login($user);

            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in register',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in login',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function check(Request $request)
    {
        // i set auth:api meddleware in this route how get direct user


        try {
            $user = auth('api')->user();
            return response()->json(compact('user'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in check',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = auth('api')->user();
            $user->delete();
            return response()->json(['message' => 'User deleted']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in delete',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
