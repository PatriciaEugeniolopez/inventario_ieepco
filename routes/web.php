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


