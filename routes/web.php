<?php

use Illuminate\Support\Facades\Route;




// Página de inicio
//  (welcome.blade.php)
Route::get('/welcome', function () {
    return view('welcome');
})->name('inicio');



// Página de login
// (login.blade.php)
Route::get('/login', function () {
    return view('login'); // Asegúrate de tener el archivo resources/views/auth/login.blade.php
})->name('login');



// Página de ¿Quiénes somos?
// (somos.blade.php)
Route::get('/somos', function () {
    return view('somos'); // resources/views/quienes_somos.blade.php
})->name('somos');



// Página de galería
// (galeria.blade.php)
Route::get('/galeria', function () {
    return view('galeria'); // resources/views/galeria.blade.php
})->name('galeria');



//pagina del inventario
// (inventario.blade.php)
Route::get('/inventario', function () {
    return view('inventario');
});
Route::post('/inventario', function () {
    return view('inventario');
});

//pagina de la caja
// (venta.blade.php)
Route::get('/venta', function () {
    return view('venta');
});
Route::post('/venta', function () {
    return view('venta');
});

//pagina de la grafica
// (grafica.blade.php)
Route::get('/grafica', function () {
    return view('grafica');
});
Route::post('/grafica', function () {
    return view('grafica');
});

//pagina de la grafica
// (grafica.blade.php)
Route::get('/Reportes', function () {
    return view('Reportes');
});
Route::post('/Reportes', function () {
    return view('Reportes');
});


// Procesar login
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

// Procesar registro
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');


Route::get('/', [PuntoVentaController::class, 'index'])->name('punto_venta');


