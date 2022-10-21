<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productores;
use App\Http\Controllers\FncGlobalesController;

class ProductoresController extends Controller
{
    protected $funcion;
    /*
    */
    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }
    //asdasdsad
    public function search(Request $request) {
        try {
            // return response()->json($request->all());

            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = Productores::where('nombre', 'like', '%'.$se.'%');
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
    public function index()
    {
        // if (auth()->user()->nivel > 1 )
            try {
                $productores = Productores::select('id', 'nombre')
                                            ->paginate(7);
                    // ->where('estacion_radio_id', auth()->user()->estacion_radio_id)
                return response()->json($productores);
            } catch (\Throwable $th) {
                $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
                return response()->json([
                    'answer' => false,
                    'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                    'php'    => $th->getMessage(),
                ]);
            }
/*         else
            return response()->json([
                'answer' => false,
                'msg' => 'No tienes acceso a este apartado.',
            ]); */
    }

// Crear Productor
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nombre' => 'required'
            ]);
                $productor =  Productores::create([
                'nombre' => $request->input('nombre'),
                'user_id' => auth()->user()->id,
            ]);
            // 'estacion_radio_id' => auth()->user()->estacion_radio_id,
            $productor->save();
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

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'nombre' => 'required'
            ]);
            $productor = Productores::find($request->input('id'));
            $productor->nombre = $request->input('nombre');
            $productor->user_id = auth()->user()->id;
            $productor->save();

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

    public function destroy($id)
    {
        try {
            // return response()->json('desde la funcion de eliminar');
            if ($productor = Productores::find($id)) {
                $productor->user_id = auth()->user()->id;
                $productor->save();
                $productor->delete();
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
        }    }
}
