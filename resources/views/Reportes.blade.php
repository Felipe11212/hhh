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



// Exportar a Excel
if (isset($_GET['exportar'])) {
    $resultado = $conexion->query("SELECT * FROM productos");
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(['ID','Nombre','Precio','Cantidad','Caducidad','fabricacion','descripcion'], NULL, 'A1');
    $fila = 2;
    while ($row = $resultado->fetch_assoc()) {
        $sheet->fromArray(array_values($row), NULL, 'A'.$fila);
        $fila++;
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="productos.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}


// Exportar a PDF
if (isset($_GET['exportar_pdf'])) {
    $resultado = $conexion->query("SELECT * FROM productos");
    $html = '<h1>Lista de Productos</h1><table border="1" cellpadding="5" cellspacing="0" style="width:100%;border-collapse:collapse;"><tr style="background-color:#f2f2f2;"><th>ID</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Caducidad</th><th>fabricacion</th><th>Descripcion</th></tr>';
    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        foreach (['id','nombre','precio','cantidad','caducidad','fabricacion','descripcion'] as $col) {
            $html .= '<td>'.htmlspecialchars($row[$col]).'</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('productos.pdf','D');
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

<body style="background-image: url('{{ asset('imgs/reporte.png') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">


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
                        <a href="inventario">Bodega <i class="bi bi-box-seam"></i></a>
                        <a href="grafica" class="btn7">Graficos <i class="bi bi-easel"></i></a>
                        <a class="aqui" class="btn7">Reportes <i class="bi bi-table"></i></a>
                        <a href="somos" class="btn7">Ventas <i class="bi bi-file-earmark-spreadsheet"></i></a>
                    </div>
                </div>
                <span class="nombre-empresa">MicroStock-Reportes</span>
            </div>
        </div>
    </header>
    <hr>

@section('content')
<div class="container">
    <h2>Reporte de Ventas</h2>

    @foreach ($ventas as $venta)
        <div class="card mb-3">
            <div class="card-header">
                Venta #{{ $venta['id'] }} - {{ $venta['fecha'] }} - Total: ${{ number_format($venta['total'], 2) }}
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($venta['detalles'] as $detalle)
                            <tr>
                                <td>{{ $detalle['nombre'] }}</td>
                                <td>${{ number_format($detalle['precio_unitario'], 2) }}</td>
                                <td>{{ $detalle['cantidad'] }}</td>
                                <td>${{ number_format($detalle['subtotal'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ url('/reportes') }}" class="btn btn-secondary mt-4">
    <i class="bi bi-file-earmark-text"></i> Ver Reportes de Ventas
</a>

       <a href="?exportar=1" class="btn2"><i class="bi bi-filetype-xls"></i> Exportar a Excel</a>
        <a href="?exportar_pdf=1" class="btn4"><i class="bi bi-file-earmark-pdf"></i> Exportar a PDF</a>
   






            </div>
        </div>
    @endforeach
</div>
@endsection









</body>
</html>

@php
$conexion->close();
@endphp
       
