@extends('layouts.app')

@section('title', 'Mis Transacciones - EcoFinder')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">📋 Mis Transacciones</h1>
            <p style="color: #64748b;">Gestiona las solicitudes a las que te has suscrito</p>
        </div>
        <div style="display:flex; column-gap:20px;">
            <a href="/dashboard" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; text-decoration:none;">
                Dashboard
            </a>
            <a href="{{ route('user.solicitudes.index') }}" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration:none;">
                Ver Solicitudes Disponibles
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
        @forelse($transacciones as $transaccion)
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                    <div style="flex: 1;">
                        <h3 style="font-size: 20px; color: #1e293b; margin-bottom: 8px;">
                            {{ $transaccion->solicitud->titulo }}
                        </h3>
                        <p style="color: #64748b; margin-bottom: 8px;">
                            <strong>Emprendedor:</strong> {{ $transaccion->solicitud->usuario->name }}
                        </p>
                        <p style="color: #64748b; margin-bottom: 12px;">
                            {{ Str::limit($transaccion->solicitud->descripcion, 120) }}
                        </p>
                        
                        <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px;">
                            <span style="display: inline-block; padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $transaccion->solicitud->material_formateado }}
                            </span>
                            {!! $transaccion->estado_badge !!}
                        </div>

                        <div style="padding: 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 16px;">
                            <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                📍 <strong>Punto de entrega:</strong> {{ $transaccion->puntoVerde->name }}
                            </p>
                            <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                📌 {{ $transaccion->puntoVerde->address }}, {{ $transaccion->puntoVerde->city }}
                            </p>
                            @if($transaccion->cantidad_ofrecida)
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                    📦 <strong>Cantidad ofrecida:</strong> {{ $transaccion->cantidad_ofrecida }} {{ $transaccion->solicitud->unidad_medida }}
                                </p>
                            @endif
                            @if($transaccion->fecha_entrega)
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                    📅 <strong>Fecha estimada:</strong> {{ $transaccion->fecha_entrega->format('d/m/Y') }}
                                </p>
                            @endif
                            @if($transaccion->notas)
                                <p style="font-size: 14px; color: #64748b; margin-top: 8px;">
                                    💬 <em>"{{ $transaccion->notas }}"</em>
                                </p>
                            @endif
                        </div>

                        <p style="font-size: 12px; color: #94a3b8;">
                            Suscrito el {{ $transaccion->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                @if($transaccion->estado === 'pendiente' || $transaccion->estado === 'confirmada')
                    <div style="display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                        @if($transaccion->estado === 'confirmada')
                            <form action="{{ route('user.transacciones.entregar', $transaccion->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                    ✓ Marcar como Entregado
                                </button>
                            </form>
                        @endif
                        
                        @if($transaccion->estado === 'pendiente')
                            <form action="{{ route('user.transacciones.cancelar', $transaccion->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('¿Seguro que quieres cancelar esta transacción?')"
                                    style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
                                    Cancelar Transaccion
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('user.solicitudes.show', $transaccion->solicitud->id) }}" 
                            style="padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; text-decoration: none; font-size: 14px;">
                            Ver Detalles
                        </a>
                    </div>
                @endif

                @if($transaccion->estado === 'entregada')
                    <div style="padding: 16px; background: #d1fae5; border-radius: 8px; margin-top: 16px;">
                        <p style="color: #065f46; font-weight: 600; text-align: center;">
                            🎉 ¡Gracias por contribuir al reciclaje!
                        </p>
                    </div>
                @endif

                @if($transaccion->estado === 'cancelada')
                    <div style="padding: 16px; background: #fee2e2; border-radius: 8px; margin-top: 16px;">
                        <p style="color: #991b1b; font-weight: 600; text-align: center;">
                            Esta transacción fue cancelada
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 48px; text-align: center;">
                <div style="font-size: 64px; margin-bottom: 16px;">📭</div>
                <h3 style="font-size: 20px; color: #1e293b; margin-bottom: 8px;">No tienes transacciones</h3>
                <p style="color: #64748b; margin-bottom: 24px;">Explora las solicitudes disponibles y suscríbete a las que te interesen</p>
                <a href="{{ route('user.solicitudes.index') }}" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration:none; display: inline-block;">
                    Ver Solicitudes Disponibles
                </a>
            </div>
        @endforelse
    </div>
@endsection