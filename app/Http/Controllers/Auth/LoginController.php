<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('username', 'password');
        $response = ['ok' => false, 'message' => 'Credenciales incorrectas'];
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $response = ['ok' => true, 'message' => 'Sesión iniciada correctamente', 'location' => route('dashboard')];
            return response()->json($response, 200);
        }
        return response()->json($response, 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['ok' => true, 'message' => 'Sesión cerrada correctamente'], 200);
    }
}
