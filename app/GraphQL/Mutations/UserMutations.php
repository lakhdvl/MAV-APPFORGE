<?php

namespace App\GraphQL\Mutations;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


final class UserMutations
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function register($rootValue, array $args)
    {
        $validator = Validator::make($args, [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $user = User::create([
            'username' => $args['username'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
        ]);

        return [
            'message' => 'success'
        ];
    }
    public function login($rootValue, array $args)
    {
        $credentials = ['email' => $args['email'], 'password' => $args['password']];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new \Exception('Unauthorized');
            }
        } catch (JWTException $e) {
            throw new \Exception('Could not create token');
        }

        return $this->respondWithToken($token);
    }
   
    public function logout($rootValue, array $args)
    {
        Auth::logout();

        return [
            'message' => 'Successfully logged out'
        ];
    }

    public function refresh($rootValue, array $args)
    {
        return $this->respondWithToken(Auth::refresh());
    }
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ];
    }
}
