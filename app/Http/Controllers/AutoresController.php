<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Autores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AutoresController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado()
    {
        try {
            $autores = Autores::Select(
                                    'id',
                                    'autor',
                                    'token'
                                    )->paginate(10);
            return ($autores);

        } catch (Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function agregar(Request $request)
    {
        try {
            // return response()->json('agregando registro');
            $validator = Validator::make($request->all(), [
                'autor' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'    => false,
                    'autor'     => $validator->errors()->first('autor'),
                ]);
            }

            $autor = new Autores();
            $autor->autor                = $request->input('autor');
            $autor->usuario_id           = Auth::user()->id;
            $autor->token                = $this->funcion->token(20);
            $autor->save();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se ha realizado exitosamente.',
            ]);

        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }
    public function search(Request $request) {
        try {
            // return response()->json($request->all());

            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = Autores::where('autor', 'like', '%'.$se.'%');
            // return response()->json('asignando variable search');

            return $busqueda->paginate(5);
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }
    public function actualizar(Request $request) {
        try {
            if ($autor = Autores::find($request->input('id')));
                    $autor->autor = $request->input('autor');
                    $autor->save();
                    return response()->json([
                        'answer' => true,
                        'msg' => 'El registro se ha actualizado exitosamente.',
                    ]);

        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }

    }
    public function eliminar($id) {
        try {
            // return response()->json('desde la funcion de eliminar');
            if (Autores::find($id)->delete()) {
                return response()->json([
                    'answer' => true,
                    'msg' => 'El registro se ha eliminado exitosamente.',
                ]);
            }
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

  /*  public function editar(Request $request)
    {
         try {
            $validator = Validator::make($request->all(), [
                'autor' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'    => false,
                    'autor'     => $validator->errors()->first('autor'),
                ]);
            }

            $buscarAutor = Autores::where('token', $request->input('token'))->first();

            $autor = Autores::find($buscarAutor->id);
            $autor->titulo               = $request->input('titulo');
            $autor->subtitulo            = $request->input('subtitulo');
            $autor->contenido            = $request->input('contenido');
            $autor->autor                = $request->input('autor');
            $autor->estatus              = $request->input('estatus');
            $autor->usuario_id           = Auth::user()->id;
            $autor->estacion_radio_id    = Auth::user()->estacion_radio_id;

            if ($request->hasFile('imagen_destacada')) {
                $programa->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            if ($request->hasFile('imagen_banner')) {
                $programa->imagen_banner     =  $this->funcion->save_file($request->file('imagen_banner'), 'upload');
            }

            $programa->save();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se ha realizado exitosamente.',
            ]);

        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    } */
}
