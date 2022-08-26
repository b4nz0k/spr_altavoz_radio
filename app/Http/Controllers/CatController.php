<?php

namespace App\Http\Controllers;

use App\Models\Programas;
use App\Models\Categorias;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;

class CatController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado ($tipo)
    {
        try {
            return Categorias::select('id', 'categoria', 'slug', 'tipo', 'estatus' , 'token')->where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('tipo', $tipo)->get();
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
                'categoria'            => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'categoria'         => $validator->errors()->first('categoria'),
                ]);
            }

            $categoria = new Categorias();
            $categoria->categoria           = $request->input('categoria');
            $categoria->descripcion         = $request->input('descripcion');
            $categoria->slug                = $request->input('slug');
            $categoria->estacion_radio_id   = \Auth::user()->estacion_radio_id;
            $categoria->tipo                = $request->input('tipo');
            $categoria->estatus             = 1;
            $categoria->token               = $this->funcion->token(20);
            $categoria->save();

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
    }

    public function detalle(Request $request)
    {
    }

    public function estacionRadio()
    {
        try {
            return EstacionRadio::select('id', 'estacion')->get();
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    // public function buscarEstacionRadio(Request $request)
    // {
    //     return EstacionRadio::select('id', 'estacion')->where('id', $request->input('estacion'))->first();
    // }

    public function programas()
    {
        try {
            return Programas::select('id', 'titulo')->where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->get();
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
