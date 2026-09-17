@extends('layouts.app')

@section('title', 'Mis Solicitudes - EcoFinder')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">💼 Mis Solicitudes</h1>
            <p style="color: #64748b;">Gestiona tus solicitudes de materiales reciclables</p>
        </div>
        <div style="display:flex; column-gap:20px;">
            <a href="/dashboard" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; text-decoration:none;">
                Dashboard
            </a>
            <a href="{{ route('user.solicitudes.create') }}" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration:none;">
                + Nueva Solicitud
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #065f46;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #991b1b;">
            ❌ {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; gap: 20px;">
        @forelse($solicitudes as $solicitud)
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                    <div style="flex: 1;">
                        <h3 style="font-size: 20px; color: #1e293b; margin-bottom: 8px;">{{ $solicitud->titulo }}</h3>
                        <p style="color: #64748b; margin-bottom: 12px;">{{ Str::limit($solicitud->descripcion, 150) }}</p>
                        
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <span style="display: inline-block; padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $solicitud->material_formateado }}
                            </span>
                            @if($solicitud->cantidad)
                                <span style="display: inline-block; padding: 4px 12px; background: #f3f4f6; color: #374151; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $solicitud->cantidad }} {{ $solicitud->unidad_medida }}
                                </span>
                            @endif
                            @if($solicitud->estado === 'activa')
                                <span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 12px; font-weight: 600;">✅ Activa</span>
                            @elseif($solicitud->estado === 'completada')
                                <span style="display: inline-block; padding: 4px 12px; background: #e0e7ff; color: #4338ca; border-radius: 12px; font-size: 12px; font-weight: 600;">🎉 Completada</span>
                            @elseif($solicitud->estado === 'cancelada')
                                <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 12px; font-weight: 600;">❌ Cancelada</span>
                            @endif
                        </div>
                    </div>
                    
                    <div style="text-align: right;">
                        <p style="font-size: 24px; font-weight: bold; color: #4a7c2c; margin-bottom: 4px;">
                            {{ $solicitud->cantidad_transacciones }}
                        </p>
                        <p style="font-size: 12px; color: #64748b;">Interesados</p>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('user.solicitudes.show', $solicitud->id) }}" style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; text-decoration: none; font-size: 14px;">
                        Ver Detalles
                    </a>
                    @if($solicitud->estado === 'activa')
                        <a href="{{ route('user.solicitudes.edit', $solicitud->id) }}" style="padding: 8px 16px; background: #f59e0b; color: white; border: none; border-radius: 6px; text-decoration: none; font-size: 14px;">
                            Editar
                        </a>
                        <form action="{{ route('user.solicitudes.cancelar', $solicitud->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                Cancelar
                            </button>
                        </form>
                        <form action="{{ route('user.solicitudes.completar', $solicitud->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                Marcar Completada
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 48px; text-align: center;">
                <div style="font-size: 64px; margin-bottom: 16px;">📋</div>
                <h3 style="font-size: 20px; color: #1e293b; margin-bottom: 8px;">No tienes solicitudes</h3>
                <p style="color: #64748b; margin-bottom: 24px;">Crea tu primera solicitud de materiales reciclables</p>
                <a href="{{ route('user.solicitudes.create') }}" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration:none; display: inline-block;">
                    + Nueva Solicitud
                </a>
            </div>
        @endforelse
    </div>
@endsection