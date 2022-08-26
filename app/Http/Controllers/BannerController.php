<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index(Banner $banner)
    {
        return response()->json($banner->all());
    }

    public function store(Request $request)
    {
        //
    }


    public function edit($id)
    {

        if ($banner = Banner::find($id))
            return response()->json([
                'banner' => $banner,
                'programames' => Banner::programasTodos()
                // 'programaMes' => Banner::programasMes()
            ]);
        else
            return response()->json('No se pudo encontrar el contenido');
    }

    public function update(Request $request)
    {
        $banner = Banner::find($request->id());
        $banner->programas_id = $request->programas_id;
        if ($banner->save())
            return response()->json(['mensaje' =>'Datos Actualizados']);
        return response()->json(['mensaje' => 'Error al guardar los datos']);
    }

    public function destroy($id)
    {
        if (Banner::find($id)->delete())
            return response()->json('Registro eliminado.');
    }
}
