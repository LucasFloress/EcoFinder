@extends('layouts.app')

@section('title', 'Solicitudes Disponibles - EcoFinder')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">🏠 Solicitudes Disponibles</h1>
            <p style="color: #64748b;">Encuentra solicitudes de reciclaje en tu municipalidad</p>
        </div>
        <div style="display:flex; column-gap:20px;">
            <a href="/dashboard" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; text-decoration:none;">
                Dashboard
            </a>
            <a href="{{ route('user.mis-transacciones') }}" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration:none;">
                Mis Transacciones
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

    {{-- Filtros --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 24px;">
        <form method="GET" style="display: flex; gap: 12px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Filtrar por Material</label>
                <select name="tipo_material" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">Todos los materiales</option>
                    <option value="plastico" {{ request('tipo_material') == 'plastico' ? 'selected' : '' }}>🔵 Plástico</option>
                    <option value="papel" {{ request('tipo_material') == 'papel' ? 'selected' : '' }}>📄 Papel/Cartón</option>
                    <option value="vidrio" {{ request('tipo_material') == 'vidrio' ? 'selected' : '' }}>🟢 Vidrio</option>
                    <option value="metal" {{ request('tipo_material') == 'metal' ? 'selected' : '' }}>⚙️ Metal</option>
                    <option value="electronico" {{ request('tipo_material') == 'electronico' ? 'selected' : '' }}>💻 Electrónico</option>
                    <option value="organico" {{ request('tipo_material') == 'organico' ? 'selected' : '' }}>🌱 Orgánico</option>
                </select>
            </div>
            <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Filtrar
            </button>
            <a href="{{ route('user.solicitudes.index') }}" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">
                Limpiar
            </a>
        </form>
    </div>

    <div style="display: grid; gap: 20px;">
        @forelse($solicitudes as $solicitud)
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <h3 style="font-size: 20px; color: #1e293b; margin-bottom: 8px;">{{ $solicitud->titulo }}</h3>
                        <p style="color: #64748b; margin-bottom: 4px;"><strong>Publicado por:</strong> {{ $solicitud->usuario->name }}</p>
                        <p style="color: #64748b; margin-bottom: 12px;">{{ Str::limit($solicitud->descripcion, 150) }}</p>
                        
                        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
                            <span style="display: inline-block; padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $solicitud->material_formateado }}
                            </span>
                            @if($solicitud->cantidad)
                                <span style="display: inline-block; padding: 4px 12px; background: #f3f4f6; color: #374151; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    {{ $solicitud->cantidad }} {{ $solicitud->unidad_medida }}
                                </span>
                            @endif
                            @if($solicitud->fecha_vencimiento)
                                <span style="display: inline-block; padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                    ⏰ Vence: {{ $solicitud->fecha_vencimiento->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        <p style="color: #64748b; font-size: 14px; margin-bottom: 8px;">
                            <strong>Puntos de entrega:</strong> {{ $solicitud->puntosVerdes()->count() }} disponibles
                        </p>
                    </div>
                    
                    <div style="text-align: right;">
                        <p style="font-size: 18px; font-weight: bold; color: #4a7c2c; margin-bottom: 4px;">
                            {{ $solicitud->cantidad_transacciones }} interesados
                        </p>
                    </div>
                </div>

                <div style="padding-top: 16px; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('user.solicitudes.show', $solicitud->id) }}" style="padding: 10px 20px; background: #4a7c2c; color: white; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                        Ver Detalles y Suscribirme
                    </a>
                </div>
            </div>
        @empty
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 48px; text-align: center;">
                <div style="font-size: 64px; margin-bottom: 16px;">🔍</div>
                <h3 style="font-size: 20px; color: #1e293b; margin-bottom: 8px;">No hay solicitudes disponibles</h3>
                <p style="color: #64748b;">No hay solicitudes activas en tu municipalidad en este momento</p>
            </div>
        @endforelse
    </div>
@endsection