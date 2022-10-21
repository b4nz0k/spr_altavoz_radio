<?php

namespace App\Http\Controllers\Usuario;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FncGlobalesController;
use App\Models\EstacionRadio;
use Illuminate\Http\Request;

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
        $usuario = User::select('id', 'name', 'email', 'estacion_radio_id', 'estatus', 'nivel', 'image')
        ->with('estacion_radio')
        ->where('id',auth()->user()->id)
        ->first();
        $usuario->letter = substr($usuario->name, 0, 1);
        if ( $nombre = explode(' ', $usuario->name)) {
            $usuario->nombre = $nombre[0] . ' ' . $nombre[1];
        }
        else {
            $usuario->nombre = $usuario->name;
        }
        $usuario->estacion = $usuario->estacion_radio->estacion;
        // $usuario->estacionnombre = EstacionRadio::find();
        return $usuario;
    }
    public function update(Request $request) {
        try {
            $user = User::find(auth()->user()->id);
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
            ]);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
