<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EcoFinder</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <!-- Lado izquierdo - Formulario -->
        <div class="form-side">
            <div class="form-wrapper">
                <h1>Crea tu Cuenta</h1>
                <p class="subtitle">Escribe aquí tus datos para crear tu cuenta.</p>

                @if($errors->any())
                    <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px; color: #991b1b;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            placeholder="Escribe tu nombre"
                            value="{{ old('nombre') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input 
                            type="text" 
                            id="apellido" 
                            name="apellido" 
                            placeholder="Escribe tu apellido"
                            value="{{ old('apellido') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="tipo_usuario">Tipo de Usuario</label>
                        <select 
                            id="tipo_usuario" 
                            name="tipo_usuario" 
                            required
                            style="margin-bottom: 8px;"
                        >
                            <option value="">Selecciona una opción</option>
                            <option value="vecino" {{ old('tipo_usuario') == 'vecino' ? 'selected' : '' }}>🏘️ Vecino - Busco productos sostenibles</option>
                            <option value="emprendedor" {{ old('tipo_usuario') == 'emprendedor' ? 'selected' : '' }}>🌱 Emprendedor - Ofrezco productos/servicios</option>
                        </select>
                        <p style="font-size: 12px; color: #666; margin: 0;">Puedes cambiar esto más adelante</p>
                    </div>

                    <div class="form-group">
                        <label for="municipality_id">Municipalidad</label>
                        <select 
                            id="municipality_id" 
                            name="municipality_id" 
                            required
                        >
                            <option value="">Selecciona tu municipalidad</option>
                            @foreach(\App\Models\User::where('role', 'admin')->where('is_active', true)->orderBy('name')->get() as $municipality)
                                <option value="{{ $municipality->id }}" {{ old('municipality_id') == $municipality->id ? 'selected' : '' }}>
                                    {{ $municipality->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Escribe tu email"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input 
                            type="tel" 
                            id="celular" 
                            name="celular" 
                            placeholder="Ej: +54 9 11 1234-5678"
                            value="{{ old('celular') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Mínimo 8 caracteres"
                            minlength="8"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Repite tu contraseña"
                            minlength="8"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-login">Registrarse</button>
                </form>

                <div class="divider">o</div>

                <p style="text-align: center; margin-top: 24px; font-size: 14px; color: #666;">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" style="color: #4a7c2c; text-decoration: none; font-weight: 600;">Ingresar</a>
                </p>
            </div>
        </div>

        <!-- Lado derecho - Imagen -->
        <div class="image-side">
            <img src="{{URL::asset('images/login-image.jpg')}}" alt="login-image">
        </div>
    </div>
</body>
</html>