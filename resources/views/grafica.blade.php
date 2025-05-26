<?php
// conexion con mysql
$conexion = new mysqli('localhost', 'root', '', 'inventario');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


// Datos para el gráfico
$productos = [];
$res = $conexion->query("SELECT nombre, cantidad, caducidad, precio FROM productos");
while ($row = $res->fetch_assoc()) {
    $productos[] = $row;
}
?>






<!-- ////////////////////////////////////////////////////////////////////////////////////// -->
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MicroStock</title>
<link rel="stylesheet" href="css/estilos.css">
<link rel="stylesheet" href="{{ asset('css/grafica.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 

</head>

<body style="background-image: url('{{ asset('imgs/graficas.png') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed;">

    

   
<center>
<!-- encabezado de la pagina -->

<header class="encabezado">
    <div class="logo">
        <div class="izquierda">
             <div class="menu">
        <button class="menu-button">
              <img src="{{ asset('imgs/logo_amarillo.png') }}" alt="Logo de la empresa" class="logo-img">
             </button>

       <div class="dropdown">
    <a href="welcome" class="btn7">inicio <i class="bi bi-house"></i></a>
    <a href="inventario" class="btn7">Bodega <i class="bi bi-box-seam"></i></a>
    <a class="aqui">Graficos <i class="bi bi-easel"></i></i></a>
    <a href="" class="btn7">Reportes <i class="bi bi-table"></i></i></a>
    <a href="" class="btn7">Ventas <i class="bi bi-file-earmark-spreadsheet"></i></a>
</div>


            
            <span class="nombre-empresa">MicroStock-Graficos</span>
        </div>
    </div>

</header>
 <hr>

</head>
<body>

<h1 class="nombre-empresa">Cantidad por Producto</h1>
<div class="fondot">
<canvas id="graficoCantidad" style="min-width: 400px;  height: 500px;"></canvas>
</div>
</div>
<br>
 <hr> <hr>
<br>


<h1 class="nombre-empresa">Precio por Producto</h1>
<div class="fondot">
<canvas id="graficoPrecio" style="min-width: 600px; height: 400px;"></canvas>
</div>
</div>
<br>
 <hr> <hr>
<br>

 
<h1 class="nombre-empresa">Productos por Fecha de Caducidad</h1>
<div class="fondot">
<canvas id="graficoCaducidad" style="min-width: 600px; height: 400px;"></canvas>
</div>
</div>
<br>
 <hr> <hr>
<br>
 

 </body>
<script>
 const productos = <?= json_encode($productos) ?>;
const labels = productos.map(p => p.nombre);
const cantidades = productos.map(p => parseInt(p.cantidad));
const precios = productos.map(p => parseFloat(p.precio));

// Procesar fechas de caducidad
const hoy = new Date();
const caducidadPorProducto = productos.map(p => {
    const fecha = new Date(p.caducidad);
    const diffTime = fecha - hoy;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return {
        nombre: p.nombre,
        fecha: p.caducidad,
        diasRestantes: diffDays
    };
});

// Agrupar por fechas
const groupByCaducidad = {};
caducidadPorProducto.forEach(p => {
    groupByCaducidad[p.fecha] = (groupByCaducidad[p.fecha] || 0) + 1;
});
const caducidadLabels = Object.keys(groupByCaducidad);
const caducidadCounts = Object.values(groupByCaducidad);

// Función genérica de gráfico
const createChart = (canvasId, chartType, label, data, bgColor, tooltipCallback, chartLabels = labels) => {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    new Chart(ctx, {
        type: chartType,
        data: {
            labels: chartLabels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: bgColor,
                borderColor: chartType === 'line' ? 'black' : undefined,
                borderWidth: chartType === 'line' ? 2 : 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: { callbacks: tooltipCallback },
                legend: {
                    labels: {
                        color: 'black',
                        font: { size: 14, weight: 'bold' }
                    }
                }
            },
            scales: chartType !== 'pie' ? {
                x: {
                    ticks: {
                        color: 'black',
                        font: { size: 12, weight: 'bold' },
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'black',
                        font: { weight: 'bold', size: 12 }
                    }
                }
            } : {}
        }
    });
};

// Gradientes para barras
const generarGradientes = () => {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.height = 400;
    return productos.map(() => {
        const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
        gradient.addColorStop(0, 'rgba(102, 255, 0, 0.8)');
        gradient.addColorStop(0.5, 'rgba(255, 242, 0, 0.8)');
        gradient.addColorStop(1, 'rgba(255, 0, 0, 0.8)');
        return gradient;
    });
};

// 📊 1. Gráfico de cantidad - tipo: 'bar'
createChart('graficoCantidad', 'bar', 'Cantidad', cantidades, generarGradientes(), {
    label: (context) => {
        const i = context.dataIndex;
        return `Producto: ${labels[i]} | Cantidad: ${cantidades[i]}`;
    }
});
// 📉 2. Gráfico de precios - tipo: 'pie'
createChart('graficoPrecio', 'pie', 'Precio', precios, [
    'rgba(255, 99, 132, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(153, 102, 255, 0.7)',
    'rgba(255, 159, 64, 0.7)'
], {
    label: (context) => {
        const i = context.dataIndex;
        return `Producto: ${labels[i]} | Precio: $${precios[i].toFixed(2)}`;
    }
}, labels); // <-- Aquí se pasan los nombres como etiquetas


// 🥧 3. Gráfico de caducidad - tipo: 'pie'
const diasRestantesPorProducto = productos.map(p => {
    const fecha = new Date(p.caducidad);
    const diffTime = fecha - hoy;
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
});

createChart('graficoCaducidad', 'line', 'Días restantes', diasRestantesPorProducto, [
    'rgba(255, 99, 132, 0.7)',
    'rgba(54, 162, 235, 0.7)',
    'rgba(255, 206, 86, 0.7)',
    'rgba(75, 192, 192, 0.7)',
    'rgba(153, 102, 255, 0.7)',
    'rgba(255, 159, 64, 0.7)'
], {
    label: (context) => {
        const i = context.dataIndex;
        const dias = diasRestantesPorProducto[i];
        const fecha = productos[i].caducidad;
        let estado = '';
        if (dias > 0) {
            estado = `Faltan ${dias} día(s)`;
        } else if (dias === 0) {
            estado = '¡Vence hoy!';
        } else {
            estado = `¡Vencido hace ${Math.abs(dias)} día(s)!`;
        }
        return `Producto: ${productos[i].nombre}\nFecha: ${fecha} | ${estado}`;
    }
}, labels);

</script>


</html>
