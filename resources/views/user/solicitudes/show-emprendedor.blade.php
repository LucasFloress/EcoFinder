@extends('layouts.app')

@section('title', 'Detalle de Solicitud - EcoFinder')

@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('user.solicitudes.index') }}" style="color: #4a7c2c; text-decoration: none; font-size: 14px;">
                ← Volver a Mis Solicitudes
            </a>
        </div>

        {{-- Información de la Solicitud --}}
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 24px;">
                <div>
                    <h1 style="font-size: 32px; color: #1e293b; margin-bottom: 12px;">{{ $solicitud->titulo }}</h1>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <span style="display: inline-block; padding: 6px 16px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 14px; font-weight: 600;">
                            {{ $solicitud->material_formateado }}
                        </span>
                        @if($solicitud->cantidad)
                            <span style="display: inline-block; padding: 6px 16px; background: #f3f4f6; color: #374151; border-radius: 12px; font-size: 14px; font-weight: 600;">
                                {{ $solicitud->cantidad }} {{ $solicitud->unidad_medida }}
                            </span>
                        @endif
                        @if($solicitud->estado === 'activa')
                            <span style="display: inline-block; padding: 6px 16px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 14px; font-weight: 600;">✅ Activa</span>
                        @elseif($solicitud->estado === 'completada')
                            <span style="display: inline-block; padding: 6px 16px; background: #e0e7ff; color: #4338ca; border-radius: 12px; font-size: 14px; font-weight: 600;">🎉 Completada</span>
                        @elseif($solicitud->estado === 'cancelada')
                            <span style="display: inline-block; padding: 6px 16px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 14px; font-weight: 600;">❌ Cancelada</span>
                        @endif
                    </div>
                </div>
                
                <div style="text-align: right;">
                    <p style="font-size: 36px; font-weight: bold; color: #4a7c2c; margin-bottom: 4px;">
                        {{ $solicitud->transacciones->count() }}
                    </p>
                    <p style="font-size: 14px; color: #64748b;">Vecinos Interesados</p>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Descripción</h3>
                <p style="color: #64748b; line-height: 1.6;">{{ $solicitud->descripcion }}</p>
            </div>

            @if($solicitud->fecha_vencimiento)
                <div style="margin-bottom: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Fecha de Vencimiento</h3>
                    <p style="color: #64748b;">⏰ {{ $solicitud->fecha_vencimiento->format('d/m/Y') }}</p>
                </div>
            @endif

            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 12px;">Puntos Verdes de Entrega</h3>
                <div style="display: grid; gap: 12px;">
                    @foreach($solicitud->puntosVerdes() as $punto)
                        <div style="padding: 16px; background: #f8fafc; border-radius: 8px;">
                            <p style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">{{ $punto->name }}</p>
                            <p style="font-size: 14px; color: #64748b;">📍 {{ $punto->address }}, {{ $punto->city }}</p>
                            @if($punto->phone)
                                <p style="font-size: 14px; color: #64748b;">📞 {{ $punto->phone }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; gap: 12px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                @if($solicitud->estado === 'activa')
                    <a href="{{ route('user.solicitudes.edit', $solicitud->id) }}" 
                        style="padding: 12px 24px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none;">
                        Editar Solicitud
                    </a>
                    <form action="{{ route('user.solicitudes.completar', $solicitud->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="padding: 12px 24px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            Marcar como Completada
                        </button>
                    </form>
                    <form action="{{ route('user.solicitudes.cancelar', $solicitud->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('¿Seguro que quieres cancelar esta solicitud?')"
                            style="padding: 12px 24px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            Cancelar Solicitud
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Lista de Vecinos Interesados --}}
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px;">
            <h2 style="font-size: 24px; color: #1e293b; margin-bottom: 24px;">👥 Vecinos Interesados</h2>

            @forelse($solicitud->transacciones as $transaccion)
                <div style="padding: 20px; background: #f8fafc; border-radius: 8px; margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div style="flex: 1;">
                            <p style="font-weight: 600; font-size: 16px; color: #1e293b; margin-bottom: 8px;">
                                {{ $transaccion->usuario->name }}
                            </p>
                            <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                📧 {{ $transaccion->usuario->email }}
                            </p>
                            @if($transaccion->usuario->phone)
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                    📞 {{ $transaccion->usuario->phone }}
                                </p>
                            @endif
                            <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                📍 Punto de entrega: <strong>{{ $transaccion->puntoVerde->name }}</strong>
                            </p>
                            @if($transaccion->cantidad_ofrecida)
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                    📦 Cantidad ofrecida: <strong>{{ $transaccion->cantidad_ofrecida }} {{ $solicitud->unidad_medida }}</strong>
                                </p>
                            @endif
                            @if($transaccion->fecha_entrega)
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 4px;">
                                    📅 Fecha estimada: <strong>{{ $transaccion->fecha_entrega->format('d/m/Y') }}</strong>
                                </p>
                            @endif
                            @if($transaccion->notas)
                                <p style="font-size: 14px; color: #64748b; margin-top: 8px;">
                                    💬 <em>"{{ $transaccion->notas }}"</em>
                                </p>
                            @endif
                        </div>
                        <div>
                            {!! $transaccion->estado_badge !!}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 48px; color: #64748b;">
                    <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
                    <p style="font-size: 16px;">Aún no hay vecinos interesados en esta solicitud</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection