<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutoresController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado($estatus)
    {
        try {
            $listAutores = [];

            $autores = Autores::all();

            foreach ($autores as $autor) {
                array_push($listAutores, [
                    'id'               => $programa->id,
                    'autor'            => $autor->autor,
                ]);
            }
            return $listAutores;

        } catch (\Throwable $th) {
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
            $validator = \Validator::make($request->all(), [
                'autor' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'    => false,
                    'autor'     => $validator->errors()->first('autor'),
                ]);
            }

            $autor = new Autor();
            $autor->autor                = $request->input('titulo');
            $autor->usuario_id           = \Auth::user()->id;
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

    public function editar(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'autor' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'    => false,
                    'autor'     => $validator->errors()->first('autor'),
                ]);
            }
            
            $buscarAutor = Autores::where('token', $request->input('token'))->first();

            $autor = Autores::find($buscarPrograma->id);
            $autor->titulo               = $request->input('titulo');
            $autor->subtitulo            = $request->input('subtitulo');
            $autor->contenido            = $request->input('contenido');
            $autor->autor                = $request->input('autor');
            $autor->estatus              = $request->input('estatus');
            $autor->usuario_id           = \Auth::user()->id;
            $autor->estacion_radio_id    = \Auth::user()->estacion_radio_id;
            
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
    }
}
