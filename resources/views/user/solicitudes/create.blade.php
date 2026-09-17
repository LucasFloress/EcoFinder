@extends('layouts.app')

@section('title', 'Nueva Solicitud - EcoFinder')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 24px;">
            <a href="{{ route('user.solicitudes.index') }}" style="color: #4a7c2c; text-decoration: none; font-size: 14px;">
                ← Volver a Mis Solicitudes
            </a>
        </div>

        <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 32px;">
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">📝 Nueva Solicitud</h1>
            <p style="color: #64748b; margin-bottom: 32px;">Crea una solicitud de materiales reciclables</p>

            @if($errors->any())
                <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #991b1b;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.solicitudes.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                        Título de la Solicitud *
                    </label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}" required 
                        style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="Ej: Busco botellas de plástico PET">
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                        Descripción *
                    </label>
                    <textarea name="descripcion" rows="4" required 
                        style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                        placeholder="Describe qué tipo de material necesitas y para qué lo usarás...">{{ old('descripcion') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                            Tipo de Material *
                        </label>
                        <select name="tipo_material" required 
                            style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                            <option value="">Seleccionar...</option>
                            <option value="plastico" {{ old('tipo_material') == 'plastico' ? 'selected' : '' }}>🔵 Plástico</option>
                            <option value="papel" {{ old('tipo_material') == 'papel' ? 'selected' : '' }}>📄 Papel/Cartón</option>
                            <option value="vidrio" {{ old('tipo_material') == 'vidrio' ? 'selected' : '' }}>🟢 Vidrio</option>
                            <option value="metal" {{ old('tipo_material') == 'metal' ? 'selected' : '' }}>⚙️ Metal</option>
                            <option value="electronico" {{ old('tipo_material') == 'electronico' ? 'selected' : '' }}>💻 Electrónico</option>
                            <option value="organico" {{ old('tipo_material') == 'organico' ? 'selected' : '' }}>🌱 Orgánico</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                            Fecha de Vencimiento (Opcional)
                        </label>
                        <input type="date" name="fecha_vencimiento" value="{{ old('fecha_vencimiento') }}"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                            Cantidad (Opcional)
                        </label>
                        <input type="number" step="0.01" min="0" name="cantidad" value="{{ old('cantidad') }}"
                            style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"
                            placeholder="Ej: 50">
                    </div>

                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                            Unidad *
                        </label>
                        <select name="unidad_medida" required 
                            style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                            <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>kg</option>
                            <option value="unidades" {{ old('unidad_medida') == 'unidades' ? 'selected' : '' }}>unidades</option>
                            <option value="litros" {{ old('unidad_medida') == 'litros' ? 'selected' : '' }}>litros</option>
                            <option value="metros" {{ old('unidad_medida') == 'metros' ? 'selected' : '' }}>metros</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 32px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 12px;">
                        Puntos Verdes de Entrega * <span style="font-weight: normal; color: #64748b;">(Selecciona al menos 1)</span>
                    </label>
                    <div style="display: grid; gap: 12px;">
                        @forelse($puntosVerdes as $punto)
                            <label style="display: flex; align-items: start; padding: 16px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                                   onmouseover="this.style.borderColor='#4a7c2c'; this.style.background='#f0f7ed'"
                                   onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='white'">
                                <input type="checkbox" name="puntos_verdes_ids[]" value="{{ $punto->id }}"
                                    {{ is_array(old('puntos_verdes_ids')) && in_array($punto->id, old('puntos_verdes_ids')) ? 'checked' : '' }}
                                    style="margin-right: 12px; width: 18px; height: 18px; margin-top: 2px;">
                                <div style="flex: 1;">
                                    <p style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">{{ $punto->name }}</p>
                                    <p style="font-size: 14px; color: #64748b;">📍 {{ $punto->address }}, {{ $punto->city }}</p>
                                </div>
                            </label>
                        @empty
                            <p style="padding: 16px; background: #fef3c7; border-radius: 8px; color: #92400e;">
                                ⚠️ No hay puntos verdes disponibles en tu municipalidad. Contacta al administrador.
                            </p>
                        @endforelse
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('user.solicitudes.index') }}" 
                        style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; text-decoration: none;">
                        Cancelar
                    </a>
                    <button type="submit" 
                        style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Crear Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection