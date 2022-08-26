<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Podcast;
use App\Models\Programas;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;
use App\Models\ProgramasEstacionRadio;
use App\Http\Controllers\FncGlobalesController;

class ProgramasController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado($estatus)
    {
        try {
            $listProgramas = [];
            $programas = '';
            if ($estatus == 'trash') {
                $programas = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 2)->orderByDesc('created_at')->get();
            } else if ($estatus == 'remove') {
                $programas = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 3)->orderByDesc('created_at')->get();
            } else if ($estatus == 'all'){
                $programas = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->orderByDesc('created_at')->get();
            }
            
            foreach ($programas as $programa) {
                $usuario = User::find($programa->usuario_id);
                $estacion_radio = EstacionRadio::find($usuario->estacion_radio_id);

                // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
                // $listEstacion = [];
                // foreach ($programas_estaciones as $programa_estacion) {
                //     $estacionRadio = EstacionRadio::find($programa_estacion->estacion_radio_id);
                //     array_push($listEstacion, [
                //         'estacion' => $estacionRadio->estacion,
                //     ]);
                // }

                array_push($listProgramas, [
                    'id'                => $programa->id,
                    'titulo'            => $programa->titulo,
                    'subtitulo'         => $programa->subtitulo,
                    'usuario'           => $usuario->name,
                    'estacion'          => $estacion_radio->estacion,
                    'token'             => $programa->token,
                    'estatus'           => $programa->estatus,
                    'autor'             => $programa->autor,
                    'name_estatus'      => ProgramasController::estatus_programa($programa->estatus),
                    'created_at'        => Carbon::parse($programa->created_at)->format('d-m-Y'),
                    'updated_at'        => Carbon::parse($programa->updated_at)->format('d-m-Y'),
                ]);
            }

            return $listProgramas;

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
                'titulo'            => ['required'],
                'subtitulo'         => ['required'],
                'contenido'         => ['required'],
                'imagen_destacada'  => ['required'],
                'imagen_banner'     => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'titulo'            => $validator->errors()->first('titulo'),
                    'subtitulo'         => $validator->errors()->first('subtitulo'),
                    'contenido'         => $validator->errors()->first('contenido'),
                    'imagen_destacada'  => $validator->errors()->first('imagen_destacada'),
                    'imagen_banner'     => $validator->errors()->first('imagen_banner'),
                ]);
            }

            $programa = new Programas();
            $programa->titulo               = $request->input('titulo');
            $programa->subtitulo            = $request->input('subtitulo');
            $programa->contenido            = $request->input('contenido');
            $programa->autor                = $request->input('autor');
            $programa->estatus              = $request->input('estatus');
            $programa->usuario_id           = \Auth::user()->id;
            $programa->estacion_radio_id    = \Auth::user()->estacion_radio_id;
            $programa->token                = $this->funcion->token(20);
            
            if ($request->hasFile('imagen_destacada')) {
                $programa->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            if ($request->hasFile('imagen_banner')) {
                $programa->imagen_banner     =  $this->funcion->save_file($request->file('imagen_banner'), 'upload');
            }

            $programa->save();

            // $estaciones = explode(',', $request->input('estaciones'));

            // foreach ($estaciones as $estacion) {
            //     $programas_estaciones = new ProgramasEstacionRadio();
            //     $programas_estaciones->programas_id         = $programa->id;
            //     $programas_estaciones->estacion_radio_id    = $estacion;
            //     $programas_estaciones->save();
            // }

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
                'titulo'            => ['required'],
                'subtitulo'         => ['required'],
                'contenido'         => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'titulo'            => $validator->errors()->first('titulo'),
                    'subtitulo'         => $validator->errors()->first('subtitulo'),
                    'contenido'         => $validator->errors()->first('contenido'),
                ]);
            }
            
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();

            $programa = Programas::find($buscarPrograma->id);
            $programa->titulo               = $request->input('titulo');
            $programa->subtitulo            = $request->input('subtitulo');
            $programa->contenido            = $request->input('contenido');
            $programa->autor                = $request->input('autor');
            $programa->estatus              = $request->input('estatus');
            $programa->usuario_id           = \Auth::user()->id;
            $programa->estacion_radio_id    = \Auth::user()->estacion_radio_id;
            
            if ($request->hasFile('imagen_destacada')) {
                $programa->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            if ($request->hasFile('imagen_banner')) {
                $programa->imagen_banner     =  $this->funcion->save_file($request->file('imagen_banner'), 'upload');
            }

            $programa->save();

            // $estaciones = explode(',', $request->input('estaciones'));
            // $listEstacionesBD = [];

            // foreach (ProgramasEstacionRadio::where('programas_id', $programa->id)->get() as $estacionBD) {
            //     array_push($listEstacionesBD, $estacionBD->estacion_radio_id);
            // }

            // $listEstaciones = [];
            // $listEstaciones_new = [];

            // foreach ($estaciones as $estacion) {
            //     array_push($listEstaciones, $estacion);
            //     array_push($listEstaciones_new, $estacion);
            // }

            // foreach ($listEstacionesBD as $estacion_bd) {
            //     foreach ($listEstaciones_new as $estacion_new) {
            //         if ($estacion_bd == $estacion_new) {
            //             $borrar = array_search($estacion_bd, $listEstaciones_new);
            //             unset($listEstaciones_new[$borrar]);
            //         }
            //     }
            // }

            // foreach ($listEstacionesBD as $estacion_bd) {
            //     foreach ($listEstaciones as $estacion) {
            //         if ($estacion_bd == $estacion) {
            //             $borrar = array_search($estacion, $listEstacionesBD);
            //             unset($listEstacionesBD[$borrar]);
            //         }
            //     }
            // }

            // if (!empty($listEstaciones_new)) {
            //     foreach ($listEstaciones_new as $estacion_new) {    
            //         $programas_estaciones = new ProgramasEstacionRadio();
            //         $programas_estaciones->programas_id         = $programa->id;
            //         $programas_estaciones->estacion_radio_id    = $estacion_new;
            //         $programas_estaciones->save();
            //     }
            // }

            // if (!empty($listEstacionesBD)) {
            //     foreach ($listEstacionesBD as $estacion_bd) {
            //         $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->where('estacion_radio_id', $estacion_bd)->first();
            //         $programas_estaciones->delete();
            //     }
            // }

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
    
    public function papelera(Request $request)
    {
        try {
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            $programa = Programas::find($buscarPrograma->id);
            $programa->estatus = 3;
            $programa->usuario_id = \Auth::user()->id;
            $programa->save();

            // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
            // foreach ($programas_estaciones as $programa_estacion) {
            //     $programa_estacion_radio = ProgramasEstacionRadio::find($programa_estacion->id);
            //     $programa_estacion_radio->estatus = 3;
            //     $programa_estacion_radio->save();
            // }

            $podcasts = Podcast::where('estacion_radio_id', \Auth::user()->id)->where('programa_id', $programa->id)->get();
            foreach ($podcasts as $podcast) {
                $pod_cast = Podcast::find($podcast->id);
                $pod_cast->estatus = 3;
                $pod_cast->save();
            }

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
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            $programa = Programas::find($buscarPrograma->id);

            $podcasts = Podcast::where('estacion_radio_id', \Auth::user()->id)->where('programa_id', $programa->id)->get();
            foreach ($podcasts as $podcast) {
                $pod_cast = Podcast::find($podcast->id);
                $pod_cast->delete();
            }

            // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
            // foreach ($programas_estaciones as $programa_estacion) {
            //     $programa_estacion_radio = ProgramasEstacionRadio::find($programa_estacion->id);
            //     $programa_estacion_radio->delete();
            // }


            $programa->delete();

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
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            $programa = Programas::find($buscarPrograma->id);
            $programa->estatus = 2;
            $programa->usuario_id = \Auth::user()->id;
            $programa->save();

            $podcasts = Podcast::where('estacion_radio_id', \Auth::user()->id)->where('programa_id', $programa->id)->get();
            foreach ($podcasts as $podcast) {
                $pod_cast = Podcast::find($podcast->id);
                $pod_cast->estatus = 2;
                $pod_cast->save();
            }

            // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
            // foreach ($programas_estaciones as $programa_estacion) {
            //     $programa_estacion_radio = ProgramasEstacionRadio::find($programa_estacion->id);
            //     $programa_estacion_radio->estatus = 2;
            //     $programa_estacion_radio->save();
            // }

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
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            $programa = Programas::find($buscarPrograma->id);
            $programa->estatus = 1;
            $programa->usuario_id = \Auth::user()->id;
            $programa->save();

            // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
            // foreach ($programas_estaciones as $programa_estacion) {
            //     $programa_estacion_radio = ProgramasEstacionRadio::find($programa_estacion->id);
            //     $programa_estacion_radio->estatus = 1;
            //     $programa_estacion_radio->save();
            // }

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
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            $programa = Programas::find($buscarPrograma->id);
            $programa->estatus = 2;
            $programa->usuario_id = \Auth::user()->id;
            $programa->save();

            $podcasts = Podcast::where('estacion_radio_id', \Auth::user()->id)->where('programa_id', $programa->id)->get();
            foreach ($podcasts as $podcast) {
                $pod_cast = Podcast::find($podcast->id);
                $pod_cast->estatus = 2;
                $pod_cast->save();
            }

            // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
            // foreach ($programas_estaciones as $programa_estacion) {
            //     $programa_estacion_radio = ProgramasEstacionRadio::find($programa_estacion->id);
            //     $programa_estacion_radio->estatus = 2;
            //     $programa_estacion_radio->save();
            // }

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
            $programa = Programas::where('token', $request->input('token'))->first();
            $listPrograma = [];

            //$programa_estacion = ProgramasEstacionRadio::where('programas_id', $programa->id)->where('estatus', $programa->estatus)->get();
            array_push($listPrograma, [
                'id'                => $programa->id,
                'estacion_radio_id' => $programa->estacion_radio_id,
                #'programas_estacion'=> $programa_estacion,
                'usuario_id'        => $programa->usuario_id,
                'titulo'            => $programa->titulo,
                'subtitulo'         => $programa->subtitulo,
                'contenido'         => $programa->contenido,
                'autor'             => $programa->autor,
                'imagen_destacada'  => $programa->imagen_destacada,
                'imagen_banner'     => $programa->imagen_banner,
                'estatus'           => $programa->estatus,
            ]);

            return $listPrograma[0];
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
            $all = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->whereIn('estatus', [1, 2])->get()->count();
            $publish = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->get()->count();
            $trash = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 2)->get()->count();
            $delete = Programas::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 3)->get()->count();


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

    public function buscarRegistros(Request $request)
    {
        try {
            $programa = Programas::where('token', $request->input('token'))->first();
            $podcast = Podcast::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('programa_id', $programa->id)->get()->count();

            return $podcast;
        } catch (\Throwable $th) {
            $this->funcion->logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
        
    }

    public function estatus_programa($estatus)
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
