<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoFinder</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <!-- Lado izquierdo - Formulario -->
        <div class="form-side">
            <div class="form-wrapper">
                <h1>Bienvenido!</h1>
                <p class="subtitle">Ingresa tu usuario y contraseña para acceder a tu cuenta.</p>

                <form action="/login" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <div class="form-header">
                            <label for="email">Email</label>
                            <a href="/forgot-password" class="forgot-link">Olvidaste tu contraseña?</a>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Esribe tu email"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Escribe tu contraseña"
                            required
                        >
                    </div>

                    <div class="remember-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Recurda mi usuario y contraseña.</label>
                    </div>

                    <button type="submit" class="btn-login">Ingresar</button>
                </form>

                <div class="divider">o</div>

                <p style="text-align: center; margin-top: 24px; font-size: 14px; color: #666;">
                    No tienes una cuenta?
                    <a href="/register" style="color: #4a7c2c; text-decoration: none; font-weight: 600;">Registrate aquí</a>
                </p>
            </div>
        </div>        

        <!-- Lado derecho - Imagen-->
        <div class="image-side">
            <img src="{{URL::asset('images/login-image.jpg')}}" alt="login-image">
        </div>
    </div>
</body>
</html>