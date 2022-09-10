<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    public function login(Request $request) : array
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $ability = $request->user()->is_admin ? ['admin:operation'] : [];
            $token = $request->user()->createToken('loginToken', $ability);
            return ['success' => ['token' => $token->plainTextToken, 'user' => $request->user()->format()]];
        }
 
        return ['message' => 'The provided credentials do not match our records'];
    }

    public function logout()
    {
        return Auth::user()->tokens()->delete();

    }
}