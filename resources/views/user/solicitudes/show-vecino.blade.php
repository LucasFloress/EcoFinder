@extends('layouts.app')

@section('title', 'Detalle de Solicitud - EcoFinder')

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('user.solicitudes.index') }}" style="color: #4a7c2c; text-decoration: none; font-size: 14px;">
                ← Volver a Solicitudes Disponibles
            </a>
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

        @if($errors->any())
            <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #991b1b;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Información de la Solicitud --}}
        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px; margin-bottom: 24px;">
            <div style="margin-bottom: 24px;">
                <h1 style="font-size: 32px; color: #1e293b; margin-bottom: 12px;">{{ $solicitud->titulo }}</h1>
                <p style="color: #64748b; margin-bottom: 12px;">
                    <strong>Publicado por:</strong> {{ $solicitud->usuario->name }}
                </p>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <span style="display: inline-block; padding: 6px 16px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 14px; font-weight: 600;">
                        {{ $solicitud->material_formateado }}
                    </span>
                    @if($solicitud->cantidad)
                        <span style="display: inline-block; padding: 6px 16px; background: #f3f4f6; color: #374151; border-radius: 12px; font-size: 14px; font-weight: 600;">
                            {{ $solicitud->cantidad }} {{ $solicitud->unidad_medida }}
                        </span>
                    @endif
                    @if($solicitud->fecha_vencimiento)
                        <span style="display: inline-block; padding: 6px 16px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 14px; font-weight: 600;">
                            ⏰ Vence: {{ $solicitud->fecha_vencimiento->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Descripción</h3>
                <p style="color: #64748b; line-height: 1.6;">{{ $solicitud->descripcion }}</p>
            </div>

            <div style="margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 12px;">
                    Puntos de Entrega Disponibles
                </h3>
                <div style="display: grid; gap: 12px;">
                    @foreach($puntosVerdesDisponibles as $punto)
                        <div style="padding: 16px; background: #f0f7ed; border-radius: 8px; border-left: 4px solid #4a7c2c;">
                            <p style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">{{ $punto->name }}</p>
                            <p style="font-size: 14px; color: #64748b;">📍 {{ $punto->address }}, {{ $punto->city }}</p>
                            @if($punto->phone)
                                <p style="font-size: 14px; color: #64748b;">📞 {{ $punto->phone }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="padding: 16px; background: #f8fafc; border-radius: 8px; margin-bottom: 24px;">
                <p style="font-size: 14px; color: #64748b;">
                    <strong>{{ $solicitud->transacciones->count() }}</strong> vecino(s) ya mostraron interés en esta solicitud
                </p>
            </div>
        </div>

        {{-- Formulario de Suscripción --}}
        @if(!$estaSuscrito)
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px;">
                <h2 style="font-size: 24px; color: #1e293b; margin-bottom: 24px;">🙋‍♂️ Me Interesa Esta Solicitud</h2>

                <form action="{{ route('user.solicitudes.suscribirse', $solicitud->id) }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 12px;">
                            Selecciona el Punto Verde donde entregarás *
                        </label>
                        <div style="display: grid; gap: 12px;">
                            @foreach($puntosVerdesDisponibles as $punto)
                                <label style="display: flex; align-items: start; padding: 16px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                                       onmouseover="this.style.borderColor='#4a7c2c'; this.style.background='#f0f7ed'"
                                       onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='white'">
                                    <input type="radio" name="punto_verde_id" value="{{ $punto->id }}" required
                                        style="margin-right: 12px; width: 18px; height: 18px; margin-top: 2px;">
                                    <div style="flex: 1;">
                                        <p style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">{{ $punto->name }}</p>
                                        <p style="font-size: 14px; color: #64748b;">📍 {{ $punto->address }}, {{ $punto->city }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                                Cantidad que puedes ofrecer (Opcional)
                            </label>
                            <input type="number" step="0.01" min="0" name="cantidad_ofrecida"
                                style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                                placeholder="Ej: 10">
                        </div>

                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                                Fecha estimada de entrega (Opcional)
                            </label>
                            <input type="date" name="fecha_entrega"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                            Notas adicionales (Opcional)
                        </label>
                        <textarea name="notas" rows="3"
                            style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                            placeholder="Puedes agregar información adicional..."></textarea>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <button type="submit"
                            style="padding: 14px 32px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer;">
                            ✓ Suscribirme a Esta Solicitud
                        </button>
                        <a href="{{ route('user.solicitudes.index') }}"
                            style="padding: 14px 32px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; text-decoration: none; display: inline-block;">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        @else
            <div style="background: #d1fae5; border-radius: 12px; padding: 32px; text-align: center;">
                <div style="font-size: 64px; margin-bottom: 16px;">✅</div>
                <h2 style="font-size: 24px; color: #065f46; margin-bottom: 12px;">Ya estás suscrito a esta solicitud</h2>
                <p style="color: #064e3b; margin-bottom: 24px;">Puedes ver el estado de tu suscripción en "Mis Transacciones"</p>
                <a href="{{ route('user.mis-transacciones') }}"
                    style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">
                    Ver Mis Transacciones
                </a>
            </div>
        @endif
    </div>
@endsection