<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => 'false',
                'data' => [
                    'message' => 'Las credenciales de acceso son incorrectas.'
                ]
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('token');

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token->plainTextToken
            ]
        ]);
    }
}
