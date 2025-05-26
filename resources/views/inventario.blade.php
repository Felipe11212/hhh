

@section('content')

@php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'inventario');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Agregar producto
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $cantidad = intval($_POST['cantidad']);
    $caducidad = trim($_POST['caducidad']);
    $fabricacion = trim($_POST['fabricacion']);
    $descripcion = trim($_POST['descripcion']);

    if ($precio >= 0 && $cantidad >= 0 && $caducidad !== '') {
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, precio, cantidad, caducidad, fabricacion, descripcion) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sdisss", $nombre, $precio, $cantidad, $caducidad, $fabricacion, $descripcion);
        $stmt->execute();
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }
}

// Editar producto
$productoEditar = null;
if (request()->has('editar')) {
    $id = intval(request('editar'));
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $productoEditar = $stmt->get_result()->fetch_assoc();
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
}


// Obtener productos
$resultado = $conexion->query("SELECT * FROM productos");
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MicroStock</title>
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     <link rel="icon" href="{{ asset('imgs/logo azul.jpg') }}">

</head>

<body style="background-image: url('{{ asset('imgs/u1.png') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">


<center>
    <header class="encabezado">
        <div class="logo">
            <div class="izquierda">
                <div class="menu">
                    <button class="menu-button">
                    <img src="{{ asset('imgs/logo_amarillo.png') }}" alt="Logo de la empresa" class="logo-img">

                    </button>
                    <div class="dropdown">
                        <a href="welcome" class="btn7">inicio <i class="bi bi-house"></i></a>
                        <a class="aqui">Bodega <i class="bi bi-box-seam"></i></a>
                        <a href="grafica" class="btn7">Graficos <i class="bi bi-easel"></i></a>
                        <a href="Reportes" class="btn7">Reportes <i class="bi bi-table"></i></a>
                        <a href="" class="btn7">Ventas <i class="bi bi-file-earmark-spreadsheet"></i></a>
                    </div>
                </div>
                <span class="nombre-empresa">MicroStock-Graficos</span>
            </div>
        </div>
    </header>
    <hr>

    <h1>MicroStock</h1>
    <form method="post">
        @csrf

<label>Nombre:<br>
    <input class="espacios" type="text" name="nombre" required value="{{ old('nombre', $productoEditar['nombre'] ?? '') }}">
</label><br><br>

<label>Precio:<br>
    <input class="espacios" type="number" step="0.01" name="precio" required value="{{ old('precio', $productoEditar['precio'] ?? '') }}">
</label><br><br>

<label>Cantidad:<br>
    <input class="espacios" type="number" name="cantidad" required value="{{ old('cantidad', $productoEditar['cantidad'] ?? '') }}">
</label><br><br>

<label>Fecha de fabricación:<br>
    <input class="espacios" type="date" name="fabricacion" required value="{{ old('fabricacion', $productoEditar['fabricacion'] ?? '') }}">
</label><br><br>

<label>Fecha de vencimiento:<br>
    <input class="espacios" type="date" name="caducidad" required value="{{ old('caducidad', $productoEditar['caducidad'] ?? '') }}">
</label><br><br>

<label>Descripción:<br>
    <input class="espacios" type="text" name="descripcion" required value="{{ old('descripcion', $productoEditar['descripcion'] ?? '') }}">
</label><br><br><br><br>

@if ($productoEditar)
    <input type="hidden" name="id" value="{{ $productoEditar['id'] }}">
@endif

<button type="submit" name="{{ $productoEditar ? 'editar' : 'agregar' }}" class="btn3">
    {{ $productoEditar ? 'Actualizar Producto' : 'Agregar Producto' }}
</button>


    </form>
    <br><br>
    <hr>
    <br><br>

    <div class="buscador">
        <input type="text" id="busqueda" placeholder="Buscar producto...">
        <button class="btn3" onclick="buscarProducto()">Buscar <i class="bi bi-search"></i></button>
    </div>
    <br><br>

    <div class="lista">
        <h2>Lista de Productos</h2>
        <table border="1" cellpadding="20">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Fecha de fabricacion</th>
                    <th>Fecha de vencimiento</th>
                    <th>Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @while ($row = $resultado->fetch_assoc())
                <tr id="producto-{{ $row['id'] }}">
                    <td>{{ $row['id'] }}</td>
                    <td>{{ htmlspecialchars($row['nombre']) }}</td>
                    <td>{{ $row['precio'] }}</td>
                    <td>{{ $row['cantidad'] }}</td>
                    <td>{{ $row['fabricacion'] }}</td>
                    <td>{{ $row['caducidad'] }}</td>
                    <td>{{ $row['descripcion'] }}</td>
                    <td>
                        <a href="?editar={{ $row['id'] }}" class="btn1"><i class="bi bi-pencil"></i> Editar</a>

                        <a href="?eliminar={{ $row['id'] }}" class="btn1" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                            <i class="bi bi-dash-circle"></i> Eliminar
                        </a>
                    </td>
                </tr>
            @endwhile
            </tbody>
        </table>
        <br>
 
    </div>
    <br><br><br>
</center>

<script>
function buscarProducto() {
    const input = document.getElementById("busqueda").value.toLowerCase();
    const filas = document.querySelectorAll("tbody tr");
    let coincidenciasEncontradas = false;
    let primeraCoincidencia = null;

    filas.forEach(fila => {
        const texto = fila.cells[1].textContent.toLowerCase(); // Compara con el nombre del producto
        if (texto.includes(input)) {
            fila.style.backgroundColor = "black"; // Resalta coincidencia
            if (!primeraCoincidencia) {
                primeraCoincidencia = fila; // Encuentra la primera coincidencia
            }
            coincidenciasEncontradas = true;
        } else {
            fila.style.backgroundColor = ""; // Quita el resalte si no coincide
        }
    });

    // Si no se encuentra ninguna coincidencia
    if (!coincidenciasEncontradas) {
        alert("No se encontraron productos que coincidan con la búsqueda.");
    } else {
        // Desplaza hasta la primera coincidencia si existe
        if (primeraCoincidencia) {
            primeraCoincidencia.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}
</script>

</body>
</html>

@php
$conexion->close();
@endphp