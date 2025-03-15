<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Validation\ValidationException;

use App\Models\User;
class AuthController extends Controller
{
    public function login(LoginAuthRequest $request) {
        $req = $request->validated();

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ]
        ], 200);
    }

    public function register(RegisterAuthRequest $request){
        $register = $request->validated();
        $defaultAvatar = 'avatars/image.png';
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> $request->password,
            'avatar' => $request->avatar ?? $defaultAvatar,
        ]);

        return response()->json($register);
    }
    public function logout(){
        Auth::logout();
        return response()->json(['message'=> 'logout succesfully'],200);
    }

}
