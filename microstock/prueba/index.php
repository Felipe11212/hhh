<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

$conexion = new mysqli('localhost', 'root', '', 'inventario');

// Agregar producto
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $conexion->query("INSERT INTO productos (nombre, precio, cantidad) VALUES ('$nombre', '$precio', '$cantidad')");
    header("Location: index.php");
    exit();
}

// Editar producto
if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $conexion->query("UPDATE productos SET nombre='$nombre', precio='$precio', cantidad='$cantidad' WHERE id=$id");
    header("Location: index.php");
    exit();
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conexion->query("DELETE FROM productos WHERE id=$id");
    header("Location: index.php");
    exit();
}

// Exportar a Excel
if (isset($_GET['exportar'])) {
    $resultado = $conexion->query("SELECT * FROM productos");
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Precio');
    $sheet->setCellValue('D1', 'Cantidad');

    $fila = 2;
    while ($row = $resultado->fetch_assoc()) {
        $sheet->setCellValue('A' . $fila, $row['id']);
        $sheet->setCellValue('B' . $fila, $row['nombre']);
        $sheet->setCellValue('C' . $fila, $row['precio']);
        $sheet->setCellValue('D' . $fila, $row['cantidad']);
        $fila++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="productos.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
}

// Exportar a PDF
if (isset($_GET['exportar_pdf'])) {
    $resultado = $conexion->query("SELECT * FROM productos");

    $html = '<h1>Lista de Productos</h1>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #f2f2f2;">
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
              </tr>';

    while ($row = $resultado->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . $row['id'] . '</td>';
        $html .= '<td>' . $row['nombre'] . '</td>';
        $html .= '<td>' . $row['precio'] . '</td>';
        $html .= '<td>' . $row['cantidad'] . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('productos.pdf', 'D');
    exit();
}

// Obtener productos
$resultado = $conexion->query("SELECT * FROM productos");

// Detectar si estamos en modo edición
$productoEditar = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $productoEditar = $conexion->query("SELECT * FROM productos WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MicroStock</title>
    <link rel="stylesheet" type="text/css" href="css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<center>
    <h1><?php echo $productoEditar ? 'Editar Producto' : 'Agregar Producto'; ?></h1>
    <h1><?php echo $productoEditar ? 'Editar Producto' : 'Agregar Producto'; ?></h1>
    <?php if ($productoEditar): ?>
        <!-- Botón para volver al formulario de agregar producto -->
        <a class="btn2" href="index.php"><i class="bi bi-plus-circle"></i> Agregar más productos</a>
    <?php endif; ?>

    <form method="post">
        <?php if ($productoEditar): ?>
            <input type="hidden" name="id" value="<?= $productoEditar['id'] ?>">
        <?php endif; ?>

        <br>
        Nombre:<br>
        <input type="text" name="nombre" required value="<?= $productoEditar['nombre'] ?? '' ?>"><br><br>

        Precio:<br>
        <input type="number" step="0.01" name="precio" required value="<?= $productoEditar['precio'] ?? '' ?>"><br><br>

        Cantidad:<br>
        <input type="number" name="cantidad" required value="<?= $productoEditar['cantidad'] ?? '' ?>"><br><br>

        <button class="btn3" name="<?= $productoEditar ? 'editar' : 'agregar' ?>">
            <?= $productoEditar ? 'Actualizar Producto' : 'Agregar Producto' ?>
        </button>
    </form>
<br><br><br><br>
    <hr>

    <div class="lista">
        <h2>Lista de Productos</h2>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th><br>
                <th>Acciones</th>
            </tr>

            <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['precio'] ?></td>
                <td><?= $row['cantidad'] ?></td>
                <td>
                    <a class="btn1" href="?editar=<?= $row['id'] ?>"><i class="bi bi-pencil"></i> Editar</a>
                    <a class="btn1" href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este producto?')"><i class="bi bi-dash-circle"></i> Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <a class="btn2" href="?exportar=1"><i class="bi bi-filetype-xls"></i> Exportar a Excel</a>
        <a class="btn2" href="?exportar_pdf=1"><i class="bi bi-file-earmark-pdf"></i> Exportar a PDF</a>
    </div>
</center>

</body>
</html>
