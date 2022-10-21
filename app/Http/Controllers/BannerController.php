<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index()
    {
        $estacionUsuario = auth()->user()->estacion_radio_id;

        $banner = Banner::Select('id', 'programas_id', 'imagen', 'status', 'link', 'orden')
                            ->orderBy('orden')
                            ->get();

        return response()->json($banner);
    }

    public function store(Request $request)
    {
        try {

            $this->validate($request,[
                'id' => 'required',
                'estado' => 'required',
                // 'fechaInicio' => 'required',
                // 'fechaFin' => 'required',
                // 'link' => 'required'
            ]);

            $estatus = 1;
            $request->input('estado') == true ? $estatus = 1 : $estatus = 0;

            // return $request->all();
            $banner = new Banner();
            $banner->programas_id = $request->input('id');
            // $banner->estacion_id = auth()->user()->estacion_radio_id;
            $banner->fecha_inicio = null;
            $banner->fecha_fin = null;
            $banner->status = $estatus;
            $banner->link = $request->input('fechahoy') . '-' . $request->input('slug');
            $banner->imagen = $request->input('imagen_banner');
            // return $banner;
            $banner->save();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se realizo correctamente.',
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

    public function orden(Request $request) {
        try {

            foreach ( $request->all() as $clave => $item ) {
                $banner = Banner::find($item['id']);
                $banner->orden = $clave + 1;
                $banner->save();
            }
            return response()->json([
                'answer' => true,
                'msg' => 'El registro se realizo correctamente.',
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

    public function estemes() {
        try {
            $banners = Banner::programasMes();
            // return $banners;
            $array = [];
            foreach ($banners as $item) {
                if (auth()->user()->estacion_radio_id == $item->estacion_radio_id) {
                    array_push($array, $item);
                }
            }

            return response()->json($array);
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        try {
                if ($banner = Banner::find($id))
                    return response()->json($banner);
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
            $estatus = 1;
            $request->input('estado') == true ? $estatus = 1 : $estatus = 0;

            // return response()->json($request->all());
            $banner = Banner::find($request->input('banner_id'));
            $banner->programas_id = $request->input('id');
            $banner->status = $estatus;
            // return response()->json($banner);
            // $banner->usuario_id = auth()->user()->id;
            $banner->save();
            return response()->json([
                'answer' => true,
                'msg' => 'El registro se realizo correctamente.',
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
            if (Banner::find($id)->delete())
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
