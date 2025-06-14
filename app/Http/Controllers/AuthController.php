<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Log; // Importar el Facade de Log

class AuthController extends Controller
{
    /**
     * Show the login form (if web).
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Process login.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form (if web).
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Process user registration.
     */
    public function register(RegisterRequest $request)
    {
        try {
            // Attempt to find the 'Operario' role. If it doesn't exist, it will be null.
            $defaultRole = Role::where('nombre_rol', 'Operario')->first();

            // If the default role is not found, log an error and return with a message.
            if (!$defaultRole) {
                Log::error('The default role "Operario" was not found during user registration.', [
                    'user_email' => $request->email
                ]);
                return back()->withInput()->withErrors(['role' => 'No se pudo asignar un rol por defecto al usuario. Por favor, contacte al administrador.']);
            }

            // Create the user with the found role_id
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $defaultRole->id,
            ]);

            Auth::login($user);

            return redirect('/dashboard');
        } catch (\Exception $e) {
            // General exception handling during registration
            Log::error("Error registering a new user: " . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->withErrors(['general' => 'Hubo un error inesperado al registrar el usuario. Por favor, inténtelo de nuevo.']);
        }
    }

    /**
     * Log out the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
