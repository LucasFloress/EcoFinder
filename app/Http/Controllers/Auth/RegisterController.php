<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Mostrar el formulario de registro
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Procesar el registro de un nuevo usuario
     * IMPORTANTE: Solo crea usuarios con rol 'user'
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'tipo_usuario' => ['required', 'in:vecino,emprendedor'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'celular' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'municipality_id' => 'required|exists:users,id',
        ]);

        // Crear usuario con rol 'user' por defecto
        $user = User::create([
            'name' => $validated['nombre'] . ' ' . $validated['apellido'],
            'email' => $validated['email'],
            'phone' => $validated['celular'],
            'user_type' => $validated['tipo_usuario'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // SIEMPRE usuario normal
            'municipality_id' => $validated['municipality_id'],
            'is_active' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', '¡Bienvenido a EcoFinder! Tu cuenta ha sido creada exitosamente.');
    }
}