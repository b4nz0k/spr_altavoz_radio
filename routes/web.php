<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\AutoresController;
use App\Http\Controllers\EstacionesController;
use App\Http\Controllers\ProductoresController;
use App\Http\Controllers\ProgramacionController;


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
        Route::get('v-select', [App\Http\Controllers\ProgramasController::class,'select']); #programa/list
        Route::get('comprobar', [App\Http\Controllers\ProgramasController::class,'comprobar']); #programa/corregir
        Route::get('corregir', [App\Http\Controllers\ProgramasController::class,'corregir']); #programa/corregir
        Route::get('list/{estatus}', [App\Http\Controllers\ProgramasController::class,'listado']); #programa/list
        Route::post('add', [App\Http\Controllers\ProgramasController::class,'agregar']); #programa/add
        Route::post('edit', [App\Http\Controllers\ProgramasController::class,'editar']); #programa/edit
        Route::post('detail', [App\Http\Controllers\ProgramasController::class,'detalle']); #programa/detail
        Route::post('search', [App\Http\Controllers\ProgramasController::class,'search']); #programa/search
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
        Route::get('programas', [App\Http\Controllers\PodcastController::class,'programas']); #podcast/programas
        Route::post('add', [App\Http\Controllers\PodcastController::class,'agregar']); #podcast/add
        Route::post('edit', [App\Http\Controllers\PodcastController::class,'editar']); #podcast/edit
        Route::post('detail', [App\Http\Controllers\PodcastController::class,'detalle']); #podcast/detail
        Route::post('search', [App\Http\Controllers\PodcastController::class,'search']); #programa/search
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
        Route::get('lista/{id}', [ProgramacionController::class, 'list']);
        Route::get('dia/{id}', [ProgramacionController::class, 'index']);
        Route::post('add', [ProgramacionController::class, 'store']);
        Route::get('edit/{id}', [ProgramacionController::class, 'edit']);
        Route::post('update', [ProgramacionController::class, 'update']);
        Route::get('del/{id}', [ProgramacionController::class, 'destroy']);
        // Route::post('insertar', [App\Http\Controllers\ProgramacionController::class,'insertar']);
        // Route::post('insertTemp', [App\Http\Controllers\progTempController::class,'create']);
        // Route::get('lista', [App\Http\Controllers\ProgramacionController::class,'lista']);
        // Route::get('listaTemp', [App\Http\Controllers\progTempController::class,'listaTemp']);
    });

    Route::get('programacion-delete/{id}', [ProgramacionController::class, 'destroy']);

    Route::prefix('cat')->group(function () {
        Route::get('list', [App\Http\Controllers\CatController::class,'listado']); #cat/list
        Route::post('search', [App\Http\Controllers\CatController::class,'search']); #cat/list
        Route::post('add', [App\Http\Controllers\CatController::class,'agregar']); #cat/add
        Route::post('update', [App\Http\Controllers\CatController::class,'actualizar']); #cat/add
        Route::get('delete/{id}', [App\Http\Controllers\CatController::class,'destroy']); #cat/add
        Route::post('edit', [App\Http\Controllers\CatController::class,'editar']); #cat/edit
        Route::post('detail', [App\Http\Controllers\CatController::class,'detalle']); #cat/detail

        Route::get('estacion-radio', [App\Http\Controllers\CatController::class,'estacionRadio']); #cat/estacion-radio;
        Route::get('programas', [App\Http\Controllers\CatController::class,'programas']); #cat/programas
    });

    Route::prefix('autor')->group(function () {
        Route::get('list', [AutoresController::class,'listado']);#autor/list
        Route::post('search', [AutoresController::class,'search']); #autor/search
        Route::post('add', [AutoresController::class,'agregar']); #autor/add
        // Route::post('edit', [AutoresController::class,'actualizar']);
        // Route::get('detail', [AutoresController::class,'detalle']);
    });
    Route::post('autor/edit', [AutoresController::class,'actualizar']);
    Route::get('autor/remove/{id}', [AutoresController::class, 'eliminar']);

    Route::prefix('estaciones')->group(function () {
        Route::get('list', [EstacionesController::class, 'index']);
        Route::post('add', [EstacionesController::class, 'store']);
        Route::post('search', [EstacionesController::class, 'search']);
        Route::post('update', [EstacionesController::class, 'update']);
        Route::get('delete/{id}', [EstacionesController::class, 'destroy']);
    });

    Route::prefix('usuario')->group(function () {
        Route::get('info', [App\Http\Controllers\Usuario\UsuarioController::class,'infoUsuario']); #usuario/info
    });
    Route::prefix('estaciones')->group(function () {
        Route::get('list', [EstacionesController::class, 'index']);
    });

    Route::prefix('categoria')->group(function() {
        Route::get('list', []);
    });
    Route::get('api-banner', [BannerController::class, 'index']);
    Route::get('api-banner-mes', [BannerController::class, 'estemes']);
    Route::post('api-orden-banner', [BannerController::class, 'orden']);
    Route::post('api-create-banner', [BannerController::class, 'store']);
    Route::get('api-edit-banner/{id}', [BannerController::class, 'edit']);
    Route::post('api-update-banner', [BannerController::class, 'update']);
    Route::get('api-delete-banner/{id}', [BannerController::class, 'destroy']);

    // Productor
    Route::get('productor/list', [ProductoresController::class, 'index']);
    Route::post('productor/search', [ProductoresController::class,'search']); #productor/search
    Route::get('productor/edit/{id}', [ProductoresController::class, 'edit']);
    Route::post('productor/add', [ProductoresController::class, 'store']);
    Route::post('productor/update', [ProductoresController::class, 'update']);
    Route::get('productor/remove/{id}', [ProductoresController::class, 'destroy']);

    // Route::get('api-usuarios', [ProgramasController::class, 'index']);
    // Route::get('api-edit-programas/{id}', [ProgramasController::class, 'edit']);
    // Route::get('api-delete-programas/{id}', [ProgramasController::class, 'destroy']);
    // Route::post('api-create-programas', [ProgramasController::class, 'store']);
    // Route::post('api-update-programas', [ProgramasController::class, 'update']);
    Route::get('api-usuarios', [UserController::class, 'index']);
    Route::get('api-edit-usuarios/{id}', [UserController::class, 'edit']);
    Route::post('api-crear-usuarios', [UserController::class, 'store']);
    Route::post('api-search-usuarios', [UserController::class, 'search']);
    Route::post('api-editar-usuarios', [UserController::class, 'update']);
    Route::get('api-eliminar-usuarios', [UserController::class, 'destroy']);
});
// Tu número de solicitud es:
// GRP-65941
// o manda
