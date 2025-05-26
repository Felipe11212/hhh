@section('content')

@php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'inventario');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar carrito
if (!session()->has('carrito')) {
    session(['carrito' => []]);
}

// Agregar producto con cantidad
if (request()->isMethod('post') && request()->has('agregar') && request()->has('id') && request()->has('cantidad')) {
    $id = (int)request('id');
    $cantidad = max(1, (int)request('cantidad'));
    $producto = $conexion->query("SELECT * FROM productos WHERE id = $id")->fetch_assoc();
    if ($producto && $producto['cantidad'] >= $cantidad) {
        $carrito = session('carrito', []); // Obtener el carrito actual o un array vacío si no existe
        if (isset($carrito[$id])) {
            $nueva_cantidad = $carrito[$id]['cantidad_seleccionada'] + $cantidad;
            if ($nueva_cantidad <= $producto['cantidad']) {
                $carrito[$id]['cantidad_seleccionada'] = $nueva_cantidad; // Actualizar la cantidad
            }
        } else {
            $producto['cantidad_seleccionada'] = $cantidad; // Establecer la cantidad seleccionada
            $carrito[$id] = $producto; // Agregar el producto al carrito
        }
        session(['carrito' => $carrito]); // Guardar el carrito actualizado en la sesión
    }
}





// Buscar productos por nombre
$buscar = request('buscar', '');
$sql = $buscar ? "SELECT * FROM productos WHERE nombre LIKE '%$buscar%'" : "SELECT * FROM productos LIMIT 10";
$resultado = $conexion->query($sql);





@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Punto de Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
     <link rel="icon" href="{{ asset('imgs/logo azul.jpg') }}">

</head>
<body class="container mt-5">
    <h2 class="mb-4">Sistema de Caja - Punto de Venta</h2>
    
@if (Auth::check())
    <p>Bienvenido, {{ Auth::user()->name }}</p>
@endif

    @if (session('exito'))
        <div class="alert alert-success">{{ session('exito') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre" value="{{ htmlspecialchars($buscar) }}">
            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
        </div>
    </form>

    <h4>Productos</h4>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad disponible</th>
                <th>Seleccionar cantidad</th>
            </tr>
        </thead>
        <tbody>
        @while ($row = $resultado->fetch_assoc())
            <tr>
                <td>{{ $row['nombre'] }}</td>
                <td>${{ number_format($row['precio'], 2) }}</td>
                <td>{{ $row['cantidad'] }}</td>
                <td>
                    <form method="POST" class="d-flex">
                        @csrf
                        <input type="hidden" name="id" value="{{ $row['id'] }}">
                        <input type="number" name="cantidad" value="1" min="1" max="{{ $row['cantidad'] }}" class="form-control form-control-sm me-2" style="width:80px;">
                        <button name="agregar" class="btn btn-sm btn-success">
                            <i class="bi bi-cart-plus"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endwhile
        </tbody>
    </table>

    <h4 class="mt-5">Compra Actual</h4>
    @if (!empty(session('carrito')))
        <form method="POST">
            @csrf
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Quitar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total = 0;
                    @endphp
                    @foreach (session('carrito') as $item)
                        @php
                        $precio = (float)$item['precio'];
                        $cantidad = (int)$item['cantidad_seleccionada'];
                        $subtotal = $precio * $cantidad;
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $item['nombre'] }}</td>
                            <td>${{ number_format($precio, 2) }}</td>
                            <td>{{ $cantidad }}</td>
                            <td>${{ number_format($subtotal, 2) }}</td>
                            <td>
                                <a href="?quitar={{ $item['id'] }}" class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="3">Total</th>
                        <th colspan="2">${{ number_format($total, 2) }}</th>
                    </tr>
                </tbody>
            </table>
            <button type="submit" name="pagar" class="btn btn-primary">
                <i class="bi bi-credit-card"></i> Pagar
            </button>
        </form>
    @else
        <p>No se han agregado productos.</p>
    @endif
</body>
</html>

@php
$conexion->close();
@endphp