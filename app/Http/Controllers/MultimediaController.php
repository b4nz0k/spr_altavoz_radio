<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Programas;
use App\Models\Categorias;
use App\Models\Multimedia;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;

class MultimediaController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado($estatus)
    {
        try {
            $listMultimedia = [];
            $blogs = '';
            if ($estatus == 'trash') {
                $multimedias = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 2)->orderByDesc('created_at')->get();
            } else if ($estatus == 'remove') {
                $multimedias = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 3)->orderByDesc('created_at')->get();
            } else if ($estatus == 'all'){
                $multimedias = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->orderByDesc('created_at')->get();
            }
            
            foreach ($multimedias as $multimeida) {
                $usuario = User::find($podcast->usuario_id);
                $estacion_radio = EstacionRadio::find($usuario->estacion_radio_id);
                $programa = Programas::find($podcast->programas_id);
                $categoria = Categorias::find($podcast->categoria_id);

                // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
                // $listEstacion = [];
                // foreach ($programas_estaciones as $programa_estacion) {
                //     $estacionRadio = EstacionRadio::find($programa_estacion->estacion_radio_id);
                //     array_push($listEstacion, [
                //         'estacion' => $estacionRadio->estacion,
                //     ]);
                // }
                array_push($listMultimedia, [
                    'id'                => $multimeida->id,
                    'titulo'            => $multimeida->titulo,
                    'subtitulo'         => $multimeida->subtitulo,
                    'usuario'           => $usuario->name,
                    'estacion'          => $estacion_radio->estacion,
                    'programa'          => $programa->titulo,
                    'categoria'         => $categoria->categoria,
                    'token'             => $multimeida->token,
                    'estatus'           => $multimeida->estatus,
                    'name_estatus'      => MultimediaController::estatus_podcast($multimeida->estatus),
                    'created_at'        => Carbon::parse($multimeida->created_at)->format('d-m-Y'),
                    'updated_at'        => Carbon::parse($multimeida->updated_at)->format('d-m-Y'),
                ]);
            }

            return $listMultimedia;

        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function papelera(Request $request)
    {
        try {
            $buscarMultimedia = Multimedia::where('token', $request->input('token'))->first();
            $multimedia = Multimedia::find($buscarMultimedia->id);
            $multimedia->estatus = 3;
            $multimedia->usuario_id = \Auth::user()->id;
            $multimedia->save();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se envio a la pepelera exitosamente.',
            ]);
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $buscarMultimedia = Multimedia::where('token', $request->input('token'))->first();
            $multimedia = Multimedia::find($buscarMultimedia->id);
            $multimedia->delete();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se envio a la pepelera exitosamente.',
            ]);
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function restaurar(Request $request)
    {
        try {
            $buscarMultimedia = Multimedia::where('token', $request->input('token'))->first();
            $multimedia = Multimedia::find($buscarMultimedia->id);
            $multimedia->estatus = 2;
            $multimedia->usuario_id = \Auth::user()->id;
            $multimedia->save();
            

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se restauro exitosamente.',
            ]);
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function publicar(Request $request)
    {
        try {
            $buscarMultimedia = Multimedia::where('token', $request->input('token'))->first();
            $multimedia = Multimedia::find($buscarMultimedia->id);
            $multimedia->estatus = 1;
            $multimedia->usuario_id = \Auth::user()->id;
            $multimedia->save();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se ha publicado exitosamente.',
            ]);
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function borrador(Request $request)
    {
        try {
            $buscarMultimedia = Multimedia::where('token', $request->input('token'))->first();
            $multimedia = Multimedia::find($buscarMultimedia->id);
            $multimedia->estatus = 2;
            $multimedia->usuario_id = \Auth::user()->id;
            $multimedia->save();

            return response()->json([
                'answer' => true,
                'msg' => 'El registro se ha pasado a borrador exitosamente.',
            ]);
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }
    
    public function detalle(Request $request)
    {
        try {
            $multimedia = Multimedia::where('token', $request->input('token'))->first();
            $listMultimedia = [];

            //$programa_estacion = ProgramasEstacionRadio::where('programas_id', $programa->id)->where('estatus', $programa->estatus)->get();
            array_push($listPodcast, [
                'id'                => $multimedia->id,
                'estacion_radio_id' => $multimedia->estacion_radio_id,
                #'programas_estacion'=> $programa_estacion,
                'usuario_id'        => $multimedia->usuario_id,
                'programas'         => $multimedia->programas_id,
                'categoria'         => $multimedia->categoria_id,
                'titulo'            => $multimedia->titulo,
                'subtitulo'         => $multimedia->subtitulo,
                'contenido'         => $multimedia->contenido,
                'productor'         => $multimedia->productor,
                'imagen_destacada'  => $multimedia->imagen_destacada,
                'video_upload'      => $multimedia->video_upload,
                'vide_iframe'       => $multimedia->video_iframe,
                'estatus'           => $multimedia->estatus,
            ]);

            return $listMultimedia[0];
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }


    public function count()
    {
        try {
            $all = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->whereIn('estatus', [1, 2])->get()->count();
            $publish = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->get()->count();
            $trash = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 2)->get()->count();
            $delete = Multimedia::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 3)->get()->count();

            return response()->json([
                'all'       => $all > 0 ? $all : 0,
                'publish'   => $publish > 0 ? $publish : 0,
                'trash'     => $trash > 0 ? $trash : 0,
                'delete'    => $delete > 0 ? $delete : 0,
            ]);
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function estatus_multimedia($estatus)
    {
        switch ($estatus) {
            case 1:
                return 'Publicado';
                break;
            case 2:
                return 'Borrador';
                break;
            case 3:
                return 'Papelera';
                break;
        }
    }
}
