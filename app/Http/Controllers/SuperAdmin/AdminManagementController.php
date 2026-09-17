<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    /**
     * Mostrar lista de administradores
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super-admin.admins.index', compact('admins'));
    }

    /**
     * Crear un nuevo administrador
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'user_type' => 'municipalidad', // ← Tipo específico para municipalidades
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verificado porque lo crea el super admin
        ]);

        return redirect()->route('super-admin.admins.index')
            ->with('success', '✅ Administrador creado exitosamente');
    }

    /**
     * Actualizar un administrador
     */
    public function update(Request $request, User $admin)
    {
        // Validar que sea un admin
        if ($admin->role !== 'admin') {
            return redirect()->back()->with('error', '❌ Este usuario no es un administrador');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('super-admin.admins.index')
            ->with('success', '✅ Administrador actualizado exitosamente');
    }

    /**
     * Eliminar un administrador
     */
    public function destroy(User $admin)
    {
        // Validar que sea un admin
        if ($admin->role !== 'admin') {
            return redirect()->back()->with('error', '❌ Este usuario no es un administrador');
        }

        // Prevenir que se elimine a sí mismo
        if ($admin->id === auth()->id()) {
            return redirect()->back()->with('error', '❌ No puedes eliminarte a ti mismo');
        }

        $admin->delete();

        return redirect()->route('super-admin.admins.index')
            ->with('success', '✅ Administrador eliminado exitosamente');
    }

    /**
     * Activar/Desactivar administrador
     */
    public function toggleStatus(User $admin)
    {
        if ($admin->role !== 'admin') {
            return redirect()->back()->with('error', '❌ Este usuario no es un administrador');
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $status = $admin->is_active ? 'activado' : 'desactivado';
        
        return redirect()->route('super-admin.admins.index')
            ->with('success', "✅ Administrador {$status} exitosamente");
    }
}