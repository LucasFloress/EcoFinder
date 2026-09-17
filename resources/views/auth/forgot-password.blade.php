<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - EcoFinder</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <!-- Lado izquierdo - Formulario -->
        <div class="form-side">
            <div class="form-wrapper">
                <h1>Olvidaste tu contraseña?</h1>
                <p class="subtitle">No te preocupes, te enviaremos los pasos para recuperarla.</p>

                <form action="/forgot-password" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Escribe tu email"
                            required
                        >
                    </div>

                    <button type="submit" class="btn-login">Restablecer contraseña</button>
                </form>

                <p style="text-align: center; margin-top: 24px; font-size: 14px; color: #666;">
                    <a href="/login" style="color: #4a7c2c; text-decoration: none; font-weight: 600;">← Login</a>
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