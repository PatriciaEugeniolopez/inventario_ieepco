<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MobiliarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\AreaAsignacionController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\MobiliarioAsignacionController;
use App\Http\Controllers\MobiliarioRentaController;
use App\Http\Controllers\MobiliarioHistorialController;


/*|--------------------------------------------------------------------------
| Ruta pública
|--------------------------------------------------------------------------*/
Route::get('/', function () {
    return redirect('/login');
});

/*|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------*/
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


/*|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::resource('usuarios', UsuarioController::class);

Route::resource('marca', MarcaController::class);

Route::resource('modelo', ModeloController::class);

Route::resource('proveedor', ProveedorController::class);

Route::resource('area_asignacion', AreaAsignacionController::class);

Route::resource('empleados', EmpleadoController::class);

Route::resource('mobiliarios', MobiliarioController::class);


// Rutas de Asignaciones
Route::resource('mobiliario_asignacion', MobiliarioAsignacionController::class);
Route::get('mobiliario_asignacion/{mobiliario_asignacion}/devolucion', [MobiliarioAsignacionController::class, 'formDevolucion'])
    ->name('mobiliario_asignacion.form_devolucion');
Route::put('mobiliario_asignacion/{mobiliario_asignacion}/devolver', [MobiliarioAsignacionController::class, 'devolver'])
    ->name('mobiliario_asignacion.devolver');
Route::get('mobiliario/verificar-stock/{id_mobiliario}', [MobiliarioAsignacionController::class, 'verificarStock'])
    ->name('mobiliario.verificar_stock');
Route::get('mobiliario-asignacion/reporte/empleado', [MobiliarioAsignacionController::class, 'reportePorEmpleado'])
    ->name('mobiliario_asignacion.reporte_empleado');
Route::get('mobiliario-asignacion/reporte/area', [MobiliarioAsignacionController::class, 'reportePorArea'])
    ->name('mobiliario_asignacion.reporte_area');

// Rutas de Rentas
Route::resource('mobiliario_renta', MobiliarioRentaController::class);
Route::put('mobiliario_renta/{mobiliario_renta}/finalizar', [MobiliarioRentaController::class, 'finalizar'])
    ->name('mobiliario_renta.finalizar');
Route::put('mobiliario_renta/{mobiliario_renta}/renovar', [MobiliarioRentaController::class, 'renovar'])
    ->name('mobiliario_renta.renovar');
Route::get('mobiliario-renta/activas', [MobiliarioRentaController::class, 'activas'])
    ->name('mobiliario_renta.activas');
Route::get('mobiliario-renta/proximas-vencer', [MobiliarioRentaController::class, 'proximasVencer'])
    ->name('mobiliario_renta.proximas_vencer');
Route::get('mobiliario-renta/reporte/costos', [MobiliarioRentaController::class, 'reporteCostos'])
    ->name('mobiliario_renta.reporte_costos');

// Rutas de Historial
Route::get('mobiliario-historial', [MobiliarioHistorialController::class, 'index'])
    ->name('mobiliario_historial.index');
Route::get('mobiliario-historial/mobiliario/{id_mobiliario}', [MobiliarioHistorialController::class, 'porMobiliario'])
    ->name('mobiliario_historial.por_mobiliario');
Route::get('mobiliario-historial/usuario/{user_id}', [MobiliarioHistorialController::class, 'porUsuario'])
    ->name('mobiliario_historial.por_usuario');
Route::get('mobiliario-historial/reporte/periodo', [MobiliarioHistorialController::class, 'reportePorPeriodo'])
    ->name('mobiliario_historial.reporte_periodo');
Route::get('mobiliario-historial/exportar', [MobiliarioHistorialController::class, 'exportar'])
    ->name('mobiliario_historial.exportar');

// Rutas adicionales de Mobiliario (stock)
Route::get('mobiliario/reporte/stock', [MobiliarioController::class, 'reporteStock'])
    ->name('mobiliario.reporte_stock');
Route::put('mobiliario/{mobiliario}/ajustar-stock', [MobiliarioController::class, 'ajustarStock'])
    ->name('mobiliario.ajustar_stock');
Route::put('mobiliario/{mobiliario}/marcar-reparacion', [MobiliarioController::class, 'marcarReparacion'])
    ->name('mobiliario.marcar_reparacion');
Route::put('mobiliario/{mobiliario}/finalizar-reparacion', [MobiliarioController::class, 'finalizarReparacion'])
    ->name('mobiliario.finalizar_reparacion');