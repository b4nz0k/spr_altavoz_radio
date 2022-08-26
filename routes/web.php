<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProgramasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes(['register' => false]);
Route::get('acceso', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('acceso', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::prefix('system')->group(function () {
        Route::post('logs', [App\Http\Controllers\FncGlobalesController::class,'logsFronted']); #system/logs
    });

    Route::prefix('ckeditor')->group(function () {
        Route::post('imagen', [App\Http\Controllers\FncGlobalesController::class,'ckeditorImagen']); #ckeditor/imagen
    });

    Route::get('gallery/{file_tipo}', [App\Http\Controllers\FncGlobalesController::class,'galeria']);

    Route::prefix('programa')->group(function () {
        Route::get('list/{estatus}', [App\Http\Controllers\ProgramasController::class,'listado']); #programa/list
        Route::post('add', [App\Http\Controllers\ProgramasController::class,'agregar']); #programa/add
        Route::post('edit', [App\Http\Controllers\ProgramasController::class,'editar']); #programa/edit
        Route::post('detail', [App\Http\Controllers\ProgramasController::class,'detalle']); #programa/detail
        Route::post('trash', [App\Http\Controllers\ProgramasController::class,'papelera']); #programa/remove
        Route::post('remove', [App\Http\Controllers\ProgramasController::class,'eliminar']); #programa/remove
        Route::post('restore', [App\Http\Controllers\ProgramasController::class,'restaurar']); #programa/restore
        Route::post('publish', [App\Http\Controllers\ProgramasController::class,'publicar']); #programa/publish
        Route::post('eraser', [App\Http\Controllers\ProgramasController::class,'borrador']); #programa/eraser
        Route::get('count', [App\Http\Controllers\ProgramasController::class,'count']); #programa/count
        Route::post('records', [App\Http\Controllers\ProgramasController::class,'buscarRegistros']); #programa/records
    });

    Route::prefix('podcast')->group(function () {
        Route::get('list/{estatus}', [App\Http\Controllers\PodcastController::class,'listado']); #podcast/list
        Route::post('add', [App\Http\Controllers\PodcastController::class,'agregar']); #podcast/add
        Route::post('edit', [App\Http\Controllers\PodcastController::class,'editar']); #podcast/edit
        Route::post('detail', [App\Http\Controllers\PodcastController::class,'detalle']); #podcast/detail
        Route::post('trash', [App\Http\Controllers\PodcastController::class,'papelera']); #podcast/remove
        Route::post('remove', [App\Http\Controllers\PodcastController::class,'eliminar']); #podcast/remove
        Route::post('restore', [App\Http\Controllers\PodcastController::class,'restaurar']); #podcast/restore
        Route::post('publish', [App\Http\Controllers\PodcastController::class,'publicar']); #podcast/publish
        Route::post('eraser', [App\Http\Controllers\PodcastController::class,'borrador']); #podcast/eraser
        Route::get('count', [App\Http\Controllers\PodcastController::class,'count']); #podcast/count
    });

    Route::prefix('blog')->group(function () {
        Route::get('list/{estatus}', [App\Http\Controllers\BlogController::class,'listado']); #blog/list
        Route::post('add', [App\Http\Controllers\BlogController::class,'agregar']); #blog/add
        Route::post('edit', [App\Http\Controllers\BlogController::class,'editar']); #blog/edit
        Route::post('detail', [App\Http\Controllers\BlogController::class,'detalle']); #blog/detail
        Route::post('trash', [App\Http\Controllers\BlogController::class,'papelera']); #blog/remove
        Route::post('remove', [App\Http\Controllers\BlogController::class,'eliminar']); #blog/remove
        Route::post('restore', [App\Http\Controllers\BlogController::class,'restaurar']); #blog/restore
        Route::post('publish', [App\Http\Controllers\BlogController::class,'publicar']); #blog/publish
        Route::post('eraser', [App\Http\Controllers\BlogController::class,'borrador']); #blog/eraser
        Route::get('count', [App\Http\Controllers\BlogController::class,'count']); #blog/count
    });

    Route::prefix('multimedia')->group(function () {
        Route::get('list/{estatus}', [App\Http\Controllers\MultimediaController::class,'listado']); #multimedia/list
        Route::post('add', [App\Http\Controllers\MultimediaController::class,'agregar']); #multimedia/add
        Route::post('edit', [App\Http\Controllers\MultimediaController::class,'editar']); #multimedia/edit
        Route::post('detail', [App\Http\Controllers\MultimediaController::class,'detalle']); #multimedia/detail
        Route::post('trash', [App\Http\Controllers\MultimediaController::class,'papelera']); #multimedia/remove
        Route::post('remove', [App\Http\Controllers\MultimediaController::class,'eliminar']); #multimedia/remove
        Route::post('restore', [App\Http\Controllers\MultimediaController::class,'restaurar']); #multimedia/restore
        Route::post('publish', [App\Http\Controllers\MultimediaController::class,'publicar']); #multimedia/publish
        Route::post('eraser', [App\Http\Controllers\MultimediaController::class,'borrador']); #multimedia/eraser
        Route::get('count', [App\Http\Controllers\MultimediaController::class,'count']); #multimedia/count
    });

    Route::prefix('programacion')->group(function () {
        Route::post('insertar', [App\Http\Controllers\ProgramacionController::class,'insertar']);
        Route::post('insertTemp', [App\Http\Controllers\progTempController::class,'create']);
        Route::get('lista', [App\Http\Controllers\ProgramacionController::class,'lista']);
        Route::get('listaTemp', [App\Http\Controllers\progTempController::class,'listaTemp']);
    });

    Route::prefix('cat')->group(function () {
        Route::get('list/{tipo}', [App\Http\Controllers\CatController::class,'listado']); #cat/list
        Route::post('add', [App\Http\Controllers\CatController::class,'agregar']); #cat/add
        Route::post('edit', [App\Http\Controllers\CatController::class,'editar']); #cat/edit
        Route::post('detail', [App\Http\Controllers\CatController::class,'detalle']); #cat/detail

        Route::get('estacion-radio', [App\Http\Controllers\CatController::class,'estacionRadio']); #cat/estacion-radio;
        Route::get('programas', [App\Http\Controllers\CatController::class,'programas']); #cat/programas
    });

    Route::prefix('autor')->group(function () {
        Route::get('list', [App\Http\Controllers\AutorController::class,'listado']);
        Route::get('add', [App\Http\Controllers\AutorController::class,'agregar']);
        Route::get('edit', [App\Http\Controllers\AutorController::class,'editar']);
        Route::get('detail', [App\Http\Controllers\AutorController::class,'detalle']);
        Route::get('remove', [App\Http\Controllers\AutorController::class,'eliminar']);
    });

    Route::prefix('usuario')->group(function () {
        Route::get('info', [App\Http\Controllers\Usuario\UsuarioController::class,'infoUsuario']); #usuario/info
    });

    Route::get('api-banner', [BannerController::class, 'index']);
    Route::get('api-edit-banner/{id}', [BannerController::class, 'edit']);
    Route::post('api-update-banner', [BannerController::class, 'update']);
    Route::get('api-delete-banner/{id}', [BannerController::class, 'destroy']);

    // Route::get('api-programas', [ProgramasController::class, 'index']);
    // Route::get('api-edit-programas/{id}', [ProgramasController::class, 'edit']);
    // Route::get('api-delete-programas/{id}', [ProgramasController::class, 'destroy']);
    // Route::post('api-create-programas', [ProgramasController::class, 'store']);
    // Route::post('api-update-programas', [ProgramasController::class, 'update']);
});
