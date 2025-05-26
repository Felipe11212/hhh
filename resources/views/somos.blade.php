<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiénes Somos</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/quienes_somos.css') }}">
</head>
<body>
    <header class="bg-dark text-white">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="#">MicroStock</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inicio') }}">Volver Al Inicio</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <section id="quienes-somos" class="py-5">
        <div class="container">
            <h1 class="text-center">¿Quiénes Somos?</h1>
            <p>En <strong>MicroStock</strong>, entendemos que la gestión eficiente del inventario es fundamental para el éxito de las pequeñas y medianas empresas. Nuestra misión es proporcionar soluciones innovadoras y accesibles que simplifiquen la administración de inventarios.</p>
            <p>Nuestro equipo está compuesto por expertos en tecnología y gestión empresarial, apasionados por ayudar a las empresas a optimizar sus procesos.</p>
            <h2>Nuestra Visión</h2>
            <p>Aspiramos a ser el aliado estratégico de las pequeñas y medianas empresas, brindando herramientas que les permitan tomar decisiones informadas y mejorar su eficiencia operativa.</p>
            <h2>Únete a Nosotros</h2>
            <button class="btn btn-warning" id="cta-button">Descubre Más</button>
        </div>
    </section>

    <section id="servicios" class="bg-light py-5">
        <div class="container">
            <h1 class="text-center">Nuestros Servicios</h1>
            <div class="row">
                <div class="ag-format-container">
                    <div class="ag-courses_box">

                        <div class="ag-courses_item">
                            <a href="#" class="ag-courses-item_link">
                                <div class="ag-courses-item_bg"></div>
                                <div class="ag-courses-item_title">
                                    Gestión De Inventarios
                                </div>
                                <div class="ag-courses-item_date-box">
                                    Optimiza la administración de tus productos y controla tu stock de manera eficiente.
                                </div>
                            </a>
                        </div>

                        <div class="ag-courses_item">
                            <a href="#" class="ag-courses-item_link">
                                <div class="ag-courses-item_bg"></div>
                                <div class="ag-courses-item_title">
                                    Reportes y Análisis
                                </div>
                                <div class="ag-courses-item_date-box">
                                    Genera reportes detallados para tomar decisiones informadas sobre tu espacio.
                                </div>
                            </a>
                        </div>

                        <div class="ag-courses_item">
                            <a href="#" class="ag-courses-item_link">
                                <div class="ag-courses-item_bg"></div>
                                <div class="ag-courses-item_title">
                                    Soporte Técnico
                                </div>
                                <div class="ag-courses-item_date-box">
                                    Se tienen en cuenta las opiniones o posibles inconvenientes sobre los clientes a la hora de anexar su inventario.
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contacto" class="py-5">
        <div class="container">
            <h1 class="text-center">Contacto</h1>
            <form id="contact-form">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Mensaje</label>
                    <textarea class="form-control" id="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
            </form>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 MicroStock. Todos los derechos reservados.</p>
    </footer>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('script/quienes_somos.js') }}"></script>
</body>
</html>
