<?php

namespace App\Http\Controllers;

use App\Models\Programas;
use App\Models\Categorias;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;
use Illuminate\Support\Facades\Validator;

class CatController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado ()
    {
        try {
            // if (auth()->user()->nivel == 4) {
            //     $categorias = Categorias::select('id', 'categoria', 'descripcion', 'slug', 'tipo', 'estatus' , 'token' );
            // }
            $categorias = Categorias::select('id', 'categoria', 'descripcion', 'slug', 'tipo', 'estatus' , 'token' );
            // ->where('estacion_radio_id', auth()->user()->estacion_radio_id);
            return response()->json($categorias->paginate(5));
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
            $validator = Validator::make($request->all(), [
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
            // $categoria->slug                = $request->input('slug');
            // $categoria->estacion_radio_id   = auth()->user()->estacion_radio_id;
            // $categoria->tipo                = $request->input('tipo');
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

    public function actualizar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'categoria'            => ['required'],
                // 'descripcion'          => ['required']
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'categoria'         => $validator->errors()->first('categoria'),
                ]);
            }

            $categoria = Categorias::find($request['id']);
            $categoria->categoria           = $request->input('categoria');
            $categoria->descripcion         = $request->input('descripcion');
            // $categoria->slug                = $request->input('slug');
            // $categoria->estacion_radio_id   = auth()->user()->estacion_radio_id;
            // $categoria->tipo                = $request->input('tipo');
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

    public function destroy($id)
    {
        try {
            if (Categorias::find($id)->delete())
                return response()->json([
                    'answer' => true,
                    'msg' => 'El registro se elimino correctamente.',
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

    public function search(Request $request) {
        try {
            // return response()->json($request->all());

            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = Categorias::where('categoria', 'like', '%'.$se.'%')
                                ->orwhere('descripcion', 'like', '%'.$se.'%');
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

    // public function buscarEstacionRadio(Request $request)
    // {
    //     return EstacionRadio::select('id', 'estacion')->where('id', $request->input('estacion'))->first();
    // }

    public function programas()
    {
        try {
            return Programas::select('id', 'titulo')->where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 1)->get();
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
