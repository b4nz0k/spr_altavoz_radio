<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;
class EstacionesController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }
    public function index(EstacionRadio $estacionRadio)
    {
        $estacionRadio->select('id', 'estacion', 'descripcion');
        return $estacionRadio->paginate(10);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'estacion'  => 'required'
            ]);
            $estacion = EstacionRadio::create();
            $estacion->estacion = $request->input('estacion');
            $estacion->descripcion = $request->input('descripcion');
            $estacion->save();
                return response()->json([
                    'answer' => true,
                    'msg' => 'El registro se ha creado exitosamente.'
                ]);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }


    public function update(Request $request)
    {
        try {
            $estacion = EstacionRadio::find($request->input('id'));
            $this->validate($request, [
                'estacion' => 'required'
            ]);
            $estacion->estacion = $request->input('estacion');
            $estacion->descripcion = $request->input('descripcion');
            $estacion->save();
                return response()->json([
                    'answer' => true,
                    'msg' => 'El registro se ha actualizado exitosamente.'
                ]);
        } catch (\Throwable $th) {

        }
    }
    public function search(Request $request) {
        try {
            // return response()->json($request->all());
            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = EstacionRadio::where('estacion', 'like', '%'.$se.'%')
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

    public function destroy($id)
    {
        try {
            // $id = $request->input('id');
            if (EstacionRadio::find($id)->delete())
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
}
