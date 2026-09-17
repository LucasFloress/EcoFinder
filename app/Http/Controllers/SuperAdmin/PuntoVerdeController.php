<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PuntoVerde;
use App\Models\User;
use Illuminate\Http\Request;

class PuntoVerdeController extends Controller
{
    public function index(Request $request)
    {
        $query = PuntoVerde::with('municipality')->orderBy('created_at', 'desc');    
        // Filtros
        if ($request->filled('municipality_id')) {
            $query->where('municipality_id', $request->municipality_id);
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $puntosVerdes = $query->get();
        
        // Cambiar de $municipalidades a $municipalities
        $municipalities = User::where('role', 'admin')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    
        return view('super-admin.puntos-verdes.index', compact('puntosVerdes', 'municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'municipality_id' => ['required', 'exists:users,id'],
            'accepted_materials' => ['nullable', 'array'],
            'status' => ['required', 'in:operativo,mantenimiento,cerrado'],
        ]);

        PuntoVerde::create($validated);

        return redirect()->route('super-admin.puntos-verdes.index')
            ->with('success', '✅ Punto Verde creado exitosamente');
    }

    public function update(Request $request, PuntoVerde $puntoVerde)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'municipality_id' => ['required', 'exists:users,id'],
            'accepted_materials' => ['nullable', 'array'],
            'status' => ['required', 'in:operativo,mantenimiento,cerrado'],
        ]);

        $puntoVerde->update($validated);

        return redirect()->route('super-admin.puntos-verdes.index')
            ->with('success', '✅ Punto Verde actualizado exitosamente');
    }

    public function destroy(PuntoVerde $puntoVerde)
    {
        $puntoVerde->delete();

        return redirect()->route('super-admin.puntos-verdes.index')
            ->with('success', '✅ Punto Verde eliminado exitosamente');
    }
}