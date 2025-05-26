@php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'inventario');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login_.css') }}">
    <link rel="icon" href="{{ asset('imgs/logo azul.jpg') }}">
    <title>Inicio de sesión y Registro</title>
</head>
<body style="background-image: url('{{ asset('imgs/u1.png') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">
    <main>
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>

            <!--Formulario de Login y Registro-->
            <div class="contenedor__login-register">

                <!-- Login -->
                <form action="{{ route('login.post') }}" method="POST" class="formulario__login">
                    @csrf
                    <h2>Iniciar Sesión</h2>
                    <input type="text" placeholder="Correo Electrónico" name="correo" required>
                    <input type="password" placeholder="Contraseña" name="contraseña" required>
                    
                    <button>Entrar</button>

                    <div style="margin-top: 10px;">
                        <a href="{{ route('inicio') }}" style="color: #007bff;">¿Olvidaste tu contraseña?</a><br>
                        <a href="{{ route('inicio') }}" style="color: #28a745;">¿Eres administrador?</a>
                    </div>
                </form>

                <!-- Register -->
                <form action="{{ route('register.post') }}" method="POST" class="formulario__register">
                    @csrf
                    <h2>Regístrarse</h2>
                    <input type="text" placeholder="Nombre completo" name="nombre" required>
                    <input type="text" placeholder="Correo Electrónico" name="correo" required>
                    <input type="text" placeholder="Usuario" name="usuario" required>
                    <input type="password" placeholder="Contraseña" name="contraseña" required>
                    <button>Regístrarse</button>
                </form>

            </div>
        </div>
    </main>
    <script src="{{ asset('script/formulario.js') }}"></script>
</body>
</html>
