<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS local desde public/css -->
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
    
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Favicon desde public/img -->
    <link rel="icon" href="{{ asset('imgs/logo azul.jpg') }}">

    <title>MicroStock</title>
</head>
<body>
<header>
    <input class="menu-icon" type="checkbox" id="menu-icon" name="menu-icon"/>
    <label for="menu-icon"></label>
    
    <nav class="nav"> 		
        <ul class="pt-5">
            <li><i class="bi bi-door-open-fill"></i> <a href="{{ url('login') }}">Login y registro</a></li>
            <li><i class="bi bi-file-person-fill"></i> <a href="{{ url('somos') }}">¿Quiénes somos?</a></li>
            <li><i class="bi bi-camera2"></i> <a href="{{ url('galeria') }}">Galería</a></li>
  
        </ul>
    </nav>
    
    <div class="image-container">
        <img src="{{ asset('imgs/logo amarillo.jpg') }}" alt="Logo">
    </div>

    <div class="section-center">
        <h1 class="mb-0">MicroStock</h1>
    </div>
</header>

<main>
    <div class="page1">
        <img src="{{ asset('imgs/fondo.webp') }}" alt="Fondo">
    </div> 

    <div class="page2wrapper">
        <div class="page2">
            <p class="content">
                Desarrollamos un sistema de gestión de inventarios para pequeñas y medianas tiendas, que permite un control automatizado del stock. Nuestra plataforma facilita el registro, actualización y monitoreo en tiempo real, evitando pérdidas por sobreinventario o desabastecimiento.
                <br><br>
                MicroStock se desarrolla en Mosquera, Cundinamarca, donde no se han implementado soluciones similares para la gestión de inventarios en pequeñas y medianas tiendas. Nuestro sistema ofrece una plataforma accesible y optimizada que facilita el control de productos, mejorando la administración y eficiencia de los negocios locales.
            </p>
            <p class="content" id="copyright">
                &copy; {{ date('Y') }} MicroStock
            </p>
        </div>
    </div>	
</main>
</body>
</html>
