<?php

namespace App\Http\Controllers\Usuario;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FncGlobalesController;

class UsuarioController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function infoUsuario()
    {
        #return User::select('id', 'name', 'email', 'slug', 'cat_departamentos_id', 'cat_tipo_usuario_id', 'estatus')->where('id', \Auth::user()->id)->first();
        $usuario = User::select('id', 'name', 'email', 'estacion_radio_id', 'estatus')
        ->with('estacion_radio')
        ->where('id', \Auth::user()->id)
        ->first();
        $usuario->letter = substr($usuario->name, 0, 1);
        $nombre = explode(' ', $usuario->name);
        $usuario->nombre = $nombre[0] . ' ' . $nombre[1];
        $usuario->estacion = $usuario->estacion_radio->estacion;
        return $usuario;
    }
}
