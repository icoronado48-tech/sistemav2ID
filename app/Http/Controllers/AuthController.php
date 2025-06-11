<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role; // Asumo que tienes un modelo Role
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest; // Necesitarás crear este Form Request
use App\Http\Requests\Auth\RegisterRequest; // Necesitarás crear este Form Request

class AuthController extends Controller
{
    // Mostrar formulario de login (si es web)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redireccionar al dashboard o página de inicio
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Mostrar formulario de registro (si es web)
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('nombre_rol', 'Operario')->first()->id, // Asigna un rol por defecto, ej. 'Operario'
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
