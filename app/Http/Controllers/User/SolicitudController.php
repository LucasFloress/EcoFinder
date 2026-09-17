<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use App\Models\Transaccion;
use App\Models\PuntoVerde;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function index(Request $request)
    {
        $userType = $request->user()->user_type;

        if ($userType === 'emprendedor') {
            $solicitudes = Solicitud::where('user_id', $request->user()->id)
                ->with('transacciones')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('user.solicitudes.mis-solicitudes', compact('solicitudes'));
        } else {
            $municipalityId = $request->user()->municipality_id;

            $query = Solicitud::where('estado', 'activa')
                ->with(['usuario', 'transacciones'])
                ->orderBy('created_at', 'desc');

            if ($request->filled('tipo_material')) {
                $query->where('tipo_material', $request->tipo_material);
            }

            $todasSolicitudes = $query->get();
            
            $solicitudes = $todasSolicitudes->filter(function($solicitud) use ($municipalityId) {
                $puntosVerdes = $solicitud->puntosVerdes();
                return $puntosVerdes->where('municipality_id', $municipalityId);
            });

            // $solicitudes =$todasSolicitudes

            return view('user.solicitudes.disponibles', compact('solicitudes'));
        }
    }

    public function create(Request $request)
    {
        if ($request->user()->user_type !== 'emprendedor') {
            abort(403, 'Solo emprendedores pueden crear solicitudes');
        }

        $municipalityId = $request->user()->municipality_id;
        $puntosVerdes = PuntoVerde::where('municipality_id', $municipalityId)
            ->where('is_active', true)
            ->where('status', 'operativo')
            ->orderBy('name')
            ->get();

        return view('user.solicitudes.create', compact('puntosVerdes'));
    }

    public function store(Request $request)
    {
        if ($request->user()->user_type !== 'emprendedor') {
            abort(403, 'Solo emprendedores pueden crear solicitudes');
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'tipo_material' => ['required', 'string'],
            'cantidad' => ['nullable', 'numeric', 'min:0'],
            'unidad_medida' => ['required', 'string'],
            'puntos_verdes_ids' => ['required', 'array', 'min:1'],
            'puntos_verdes_ids.*' => ['exists:puntos_verdes,id'],
            'fecha_vencimiento' => ['nullable', 'date', 'after:today'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['estado'] = 'activa';

        Solicitud::create($validated);

        return redirect()->route('user.solicitudes.index')
            ->with('success', '✅ Solicitud creada exitosamente');
    }

    public function show(Request $request, Solicitud $solicitud)
    {
        $userType = $request->user()->user_type;

        if ($userType === 'emprendedor') {
            if ($solicitud->user_id !== $request->user()->id) {
                abort(403, 'No autorizado');
            }

            $solicitud->load(['transacciones.usuario', 'transacciones.puntoVerde']);
            return view('user.solicitudes.show-emprendedor', compact('solicitud'));
        } else {
            $solicitud->load(['usuario', 'transacciones']);
            
            $estaSuscrito = Transaccion::where('solicitud_id', $solicitud->id)
                ->where('user_id', $request->user()->id)
                ->exists();

            $municipalityId = $request->user()->municipality_id;
            $puntosVerdesDisponibles = $solicitud->puntosVerdes()
                ->where('municipality_id', $municipalityId);

            return view('user.solicitudes.show-vecino', compact('solicitud', 'estaSuscrito', 'puntosVerdesDisponibles'));
        }
    }

    public function edit(Request $request, Solicitud $solicitud)
    {
        if ($request->user()->user_type !== 'emprendedor' || $solicitud->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $municipalityId = $request->user()->municipality_id;
        $puntosVerdes = PuntoVerde::where('municipality_id', $municipalityId)
            ->where('is_active', true)
            ->where('status', 'operativo')
            ->orderBy('name')
            ->get();

        return view('user.solicitudes.edit', compact('solicitud', 'puntosVerdes'));
    }

    public function update(Request $request, Solicitud $solicitud)
    {
        if ($request->user()->user_type !== 'emprendedor' || $solicitud->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'tipo_material' => ['required', 'string'],
            'cantidad' => ['nullable', 'numeric', 'min:0'],
            'unidad_medida' => ['required', 'string'],
            'puntos_verdes_ids' => ['required', 'array', 'min:1'],
            'puntos_verdes_ids.*' => ['exists:puntos_verdes,id'],
            'fecha_vencimiento' => ['nullable', 'date', 'after:today'],
        ]);

        $solicitud->update($validated);

        return redirect()->route('user.solicitudes.index')
            ->with('success', '✅ Solicitud actualizada exitosamente');
    }

    public function destroy(Request $request, Solicitud $solicitud)
    {
        if ($request->user()->user_type !== 'emprendedor' || $solicitud->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $solicitud->delete();

        return redirect()->route('user.solicitudes.index')
            ->with('success', '✅ Solicitud eliminada');
    }

    public function suscribirse(Request $request, Solicitud $solicitud)
    {
        if ($request->user()->user_type !== 'vecino') {
            abort(403, 'Solo vecinos pueden suscribirse a solicitudes');
        }

        $transaccionExistente = Transaccion::where('solicitud_id', $solicitud->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($transaccionExistente) {
            return redirect()->back()
                ->with('error', '❌ Ya estás suscrito a esta solicitud');
        }

        if ($solicitud->estado !== 'activa') {
            return redirect()->back()
                ->with('error', '❌ Esta solicitud ya no está activa');
        }

        $validated = $request->validate([
            'punto_verde_id' => ['required', 'exists:puntos_verdes,id'],
            'cantidad_ofrecida' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
            'fecha_entrega' => ['nullable', 'date', 'after:today'],
        ]);

        if (!in_array($validated['punto_verde_id'], $solicitud->puntos_verdes_ids)) {
            return redirect()->back()
                ->with('error', '❌ Punto verde no válido para esta solicitud');
        }

        Transaccion::create([
            'solicitud_id' => $solicitud->id,
            'user_id' => $request->user()->id,
            'punto_verde_id' => $validated['punto_verde_id'],
            'cantidad_ofrecida' => $validated['cantidad_ofrecida'] ?? null,
            'notas' => $validated['notas'] ?? null,
            'fecha_entrega' => $validated['fecha_entrega'] ?? null,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('user.mis-transacciones')
            ->with('success', '✅ Te has suscrito exitosamente a la solicitud');
    }

    public function misTransacciones(Request $request)
    {
        if ($request->user()->user_type !== 'vecino') {
            abort(403, 'Solo vecinos tienen transacciones');
        }

        $transacciones = Transaccion::where('user_id', $request->user()->id)
            ->with(['solicitud.usuario', 'puntoVerde'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.solicitudes.mis-transacciones', compact('transacciones'));
    }

    public function cancelarTransaccion(Request $request, Transaccion $transaccion)
    {
        if ($transaccion->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $transaccion->update(['estado' => 'cancelada']);

        return redirect()->route('user.mis-transacciones')
            ->with('success', '✅ Transacción cancelada');
    }

    public function marcarEntregada(Request $request, Transaccion $transaccion)
    {
        if ($transaccion->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $transaccion->update(['estado' => 'entregada']);

        return redirect()->route('user.mis-transacciones')
            ->with('success', '✅ Entrega confirmada');
    }

    public function cancelar(Request $request, Solicitud $solicitud)
    {
        if ($request->user()->user_type !== 'emprendedor' || $solicitud->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $solicitud->update(['estado' => 'cancelada']);

        return redirect()->route('user.solicitudes.index')
            ->with('success', '✅ Solicitud cancelada');
    }

    public function completar(Request $request, Solicitud $solicitud)
    {
        if ($request->user()->user_type !== 'emprendedor' || $solicitud->user_id !== $request->user()->id) {
            abort(403, 'No autorizado');
        }

        $solicitud->update(['estado' => 'completada']);

        return redirect()->route('user.solicitudes.index')
            ->with('success', '✅ Solicitud marcada como completada');
    }
}