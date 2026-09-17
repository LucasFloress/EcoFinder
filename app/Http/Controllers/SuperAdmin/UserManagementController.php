<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Mostrar lista de usuarios (vecinos y emprendedores)
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener todas las municipalidades para el select
        $municipalities = User::where('role', 'admin')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('super-admin.users.index', compact('users', 'municipalities'));
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'user_type' => ['required', 'in:vecino,emprendedor'],
            'municipality_id' => ['required', 'exists:users,id'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'user_type' => $validated['user_type'],
            'municipality_id' => $validated['municipality_id'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('super-admin.users.index')
            ->with('success', '✅ Usuario creado exitosamente');
    }

    /**
     * Actualizar un usuario
     */
    public function update(Request $request, User $user)
    {
        // Validar que sea un usuario normal
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', '❌ Este no es un usuario normal');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'user_type' => ['required', 'in:vecino,emprendedor'],
            'municipality_id' => ['required', 'exists:users,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->user_type = $validated['user_type'];
        $user->municipality_id = $validated['municipality_id'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('super-admin.users.index')
            ->with('success', '✅ Usuario actualizado exitosamente');
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(User $user)
    {
        // Validar que sea un usuario normal
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', '❌ Este no es un usuario normal');
        }

        // Prevenir que se elimine a sí mismo
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', '❌ No puedes eliminarte a ti mismo');
        }

        $user->delete();

        return redirect()->route('super-admin.users.index')
            ->with('success', '✅ Usuario eliminado exitosamente');
    }

    /**
     * Activar/Desactivar usuario
     */
    public function toggleStatus(User $user)
    {
        if ($user->role !== 'user') {
            return redirect()->back()->with('error', '❌ Este no es un usuario normal');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activado' : 'desactivado';
        
        return redirect()->route('super-admin.users.index')
            ->with('success', "✅ Usuario {$status} exitosamente");
    }
}