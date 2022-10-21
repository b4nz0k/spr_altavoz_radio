<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Podcast;
use App\Models\Programas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;
use App\Http\Controllers\FncGlobalesController;

class ProgramasController extends Controller
{
    protected $funcion;
    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }
    public function comprobar() {
        return response()->json([
            'answer' => false,
            'msg' => 'Todo va bien!',
        ]);
    }
    public function corregir() {
        try {
            // Corrigiendo todos los programas en los tokens
        $programas = Programas::paginate(50);
        foreach ($programas as $programa) {
            // Buscara los tokens parecidos
            $token = $this->funcion->token(20);
            $programamod = Programas::find($programa->id);
            $programamod->token = $token;
            $programamod->save();
        }
        foreach ($programas as $programa) {
            $programaslug = Programas::where('slug', $programa->slug)->get();
            if ($programaslug->count() > 1) {
                $token = $programaslug->first()->token;
                foreach ($programaslug as $item) {
                    $p = Programas::find($item->id);
                    $p->token = $token;
                    $p->save();
                }
            }
        }
        return $programas;
/*         $programasSlug= Programas::where();
        // Saber si hay token nulos
 */

            // return 'corrigiendo ' . $c;
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }
    public function select() {
        try {
            // Seleccionar los programas de este mes
            if (auth()->user()->nivel ==4)

                $programas = Programas::select(
                                            'id',
                                            'titulo',
                                            'subtitulo',
                                            'usuario_id',
                                            'estacion_radio_id',
                                            'token',
                                            'estatus',
                                            'autor',
                                            'estaciones_ids',
                                            'created_at'
                                            )
                                        ->where('estacion_radio_id', auth()->user()->estacion_radio_id)
                                        ->whereDate('created_at', '>=', now()->subDays(30) )
                                        ->orderByDesc('created_at')
                                        ->get();
            else
                $programas = Programas::select(
                                        'id',
                                        'titulo',
                                        'subtitulo',
                                        'usuario_id',
                                        'estacion_radio_id',
                                        'token',
                                        'estatus',
                                        'autor',
                                        'estaciones_ids',
                                        'created_at'
                                        )
                                        ->whereDate('created_at', '>=', now()->subDays(30) )
                                        ->orderByDesc('created_at')
                                        ->get();


            return response()->json($programas);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function listado($estatus)
    {
        if ($estatus == 'trash') {
            $est = 2;
        } elseif ($estatus == 'remove') {
            $est = 3;
        } elseif ($estatus == 'all') {
            $est = 1;
        }

        try {
            $listProgramas = [];
                $programas = Programas::select(
                                        'id',
                                        'titulo',
                                        'subtitulo',
                                        'usuario_id',
                                        'estacion_radio_id',
                                        'imagen_destacada',
                                        'token',
                                        'estatus',
                                        'autor',
                                        'estaciones_ids',
                                        'created_at'
                                        )
                                    ->where('estacion_radio_id', auth()->user()->estacion_radio_id)
                                    ->where('estatus', $est)
                                    ->orderBy('id', 'DESC')
                                    ->paginate(8);

            return $programas;
            foreach ($programas->all() as $programa) {
                $usuario = User::find($programa->usuario_id);
                $estacion_radio = EstacionRadio::find($usuario->estacion_radio_id);

                    array_push($listProgramas, [
                    'id'                => $programa->id,
                    'titulo'            => $programa->titulo,
                    'subtitulo'         => $programa->subtitulo,
                    'usuario'           => $usuario->name,
                    'estacion'          => $estacion_radio->estacion,
                    'token'             => $programa->token,
                    'estatus'           => $programa->estatus,
                    'autor'             => $programa->autornombre,
                    'estaciones_id'     => $programa->estaciones_ids,
                    'estaciones_lista'  => $programa->estacioneslista,
                    'name_estatus'      => ProgramasController::estatus_programa($programa->estatus),
                    'created_at'        => Carbon::parse($programa->created_at)->format('d-m-Y'),
                    'updated_at'        => Carbon::parse($programa->updated_at)->format('d-m-Y'),
                ]);
            }
            return ($programas);

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
            //Asignamos las publicaciones que vamos a tener
            $token = $this->funcion->token(20); //Obtenemos el mismo token de todas las publicaciones a editar
            if ($estaciones = array_map('intval', explode( ',', $request->input('estaciones_ids')))) {

                foreach ($estaciones as $item) {
                    $programa = new Programas();
                    $programa->titulo               = $request->input('titulo');
                    $programa->subtitulo            = $request->input('subtitulo');
                    $programa->contenido            = $request->input('contenido');
                    $programa->autor                = $request->input('autor');
                    $programa->estatus              = $request->input('estatus');
                    $programa->estaciones_ids       = $request->input('estaciones_ids');
                    $programa->usuario_id           = auth()->user()->id;
                    $programa->estacion_radio_id    = $item;
                    $programa->token                = $token;
                    $programa->slug                 = Str::slug($request->input('titulo'));

                    if ($request->hasFile('imagen_destacada')) {
                        $programa->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'img/programas');
                    }
                    if ($request->hasFile('imagen_banner')) {
                        $programa->imagen_banner     =  $this->funcion->save_file($request->file('imagen_banner'), 'img/programas');
                    }
                    $programa->save();

                }
            }
            else {
                $programa = new Programas();
                $programa->titulo               = $request->input('titulo');
                $programa->subtitulo            = $request->input('subtitulo');
                $programa->contenido            = $request->input('contenido');
                $programa->autor                = $request->input('autor');
                $programa->estatus              = $request->input('estatus');
                $programa->estaciones_ids       = $request->input('estaciones_ids');
                $programa->usuario_id           = auth()->user()->id;
                $programa->estacion_radio_id    = auth()->user()->estacion_radio_id;
                $programa->token                = $token;
                $programa->slug                 = Str::slug($request->input('titulo'));

                if ($request->hasFile('imagen_destacada')) {
                    $programa->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'img/programas');
                }
                if ($request->hasFile('imagen_banner')) {
                    $programa->imagen_banner     =  $this->funcion->save_file($request->file('imagen_banner'), 'img/programas');
                }
                $programa->save();
            }
            // return $array;
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
    public function search(Request $request) {
        try {
            // return response()->json($request->all());
            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = Programas::where('titulo', 'like', '%'.$se.'%')
                                    ->orwhere('subtitulo', 'like', '%'.$se.'%');

            // return response()->json('asignando variable search');
            return $busqueda->paginate(10);
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
            // return $request->all();
            $programaId = Programas::find($request->input('id'));
            // return $programaId;
            if ($programaId->token == null)
            {
                $programaId->token = $this->funcion->token(20);
                $programaId->save();
            }

            $this->validate($request, [
                'titulo'            => ['required'],
                'subtitulo'         => ['required'],
                'contenido'         => ['required'],
            ]);
            $programaToken = Programas::where('token',$programaId->token )
            ->where('slug', $programaId->slug)->get();
            $array = [];
            foreach ($programaToken as $item) {
                array_push($array, $item);
                $programa = Programas::find($item->id);
                $programa->titulo               = $request->input('titulo');
                $programa->subtitulo            = $request->input('subtitulo');
                $programa->contenido            = $request->input('contenido');
                $programa->autor                = $request->input('autor');
                $programa->estatus              = $request->input('estatus');
                $programa->estaciones_ids       = $request->input('estaciones_ids');
                $programa->usuario_id           = auth()->user()->id;
                $programa->slug                 = Str::slug($request->input('titulo'));


                if ($request->hasFile('imagen_destacada')) {
                    $programa->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'img/programas');
                }
                if ($request->hasFile('imagen_banner')) {
                    $programa->imagen_banner     =  $this->funcion->save_file($request->file('imagen_banner'), 'img/programas');
                }

                $programa->save();
            }
            // return $array;

            // return 'hasta aqui todo bien';

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
            $buscarPrograma = Programas::where('token', $request->input('token'))->get();
            $contadorPodcast = 0;
            foreach ($buscarPrograma as $item) {
                $contadorPodcast += Podcast::where('programas_id', $item->id)->count();
            }
            return response()->json([
                'answer' => true,
                'msg' => $buscarPrograma->count(),
                'podcast' => $contadorPodcast,
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

    // antes de eliminar
    public function eliminar(Request $request)
    {
        // return response()->json('eliminando programa..');
        try {
            $countPodcast = 0;
            $countProgramas = 0;
            $programas = Programas::where('token', $request->input('token'))->get();
            foreach ($programas as $programa) {
                // Eliminamos los podcast y despues el programa
                if($countPodcast += Podcast::where('programas_id', $programa->id)->count() > 0) {
                    Podcast::where('programas_id', $programa->id)->delete();
                }
            }
            if (Programas::where('token', $request->input('token'))->delete()) {
                $countProgramas = $programas->count();
                return response()->json([
                    'answer' => true,
                    'msg' => 'Se eliminaron '. $countPodcast.' Podcast y '.$countProgramas. ' Programas',
                ]);
            }
            else {
                return response()->json([
                    'answer' => false,
                    'msg' => 'Error al eliminar el programas'
                ]);
            }


            // $programas = Programas::where('token', $request->input('token'))->get();
/*             return 'encontraste ' . count($programas) . $programas;
            $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            $programa = Programas::find($buscarPrograma->id);

            $podcasts = Podcast::where('estacion_radio_id', auth()->user()->id)->where('programa_id', $programa->id)->get();
            foreach ($podcasts as $podcast) {
                $pod_cast = Podcast::find($podcast->id);
                $pod_cast->delete();
            }
            $programa->delete();
            return response()->json([
                'answer' => true,
                'msg' => 'El registro se envio a la pepelera exitosamente.',
            ]); */
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
            $programa->usuario_id = auth()->user()->id;
            $programa->save();

            $podcasts = Podcast::where('estacion_radio_id', auth()->user()->id)->where('programa_id', $programa->id)->get();
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
            // $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            // $programa = Programas::find($buscarPrograma->id);
            if ($programa = Programas::find($request->input('id'))) {
                $programa->estatus = 1;
                $programa->usuario_id = auth()->user()->id;
                $programa->save();
            }

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
        // return response()->json('Accediendo al borrador ' .  $request->input('token') . ' ' .  $request->input('id'));
        try {
            if ($programa = Programas::find($request->input('id'))) {
                $programa->estatus = 2;
                $programa->usuario_id = auth()->user()->id;
                $programa->save();
            }

            // $buscarPrograma = Programas::where('token', $request->input('token'))->first();
            //     $programa = Programas::find($buscarPrograma->id);



            if ($podcasts = Podcast::where('estacion_radio_id', auth()->user()->id)->where('programas_id', $programa->id)->get()) {
                foreach ($podcasts as $podcast) {
                    if ($pod_cast = Podcast::find($podcast->id)) {
                        $pod_cast->estatus = 2;
                        $pod_cast->save();
                    }
                }
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
                'estaciones_ids'    => $programa->estaciones_ids,
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
            $all = Programas::where('estacion_radio_id', auth()->user()->estacion_radio_id)->whereIn('estatus', [1, 2])->get()->count();
            $publish = Programas::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 1)->get()->count();
            $trash = Programas::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 2)->get()->count();
            $delete = Programas::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 3)->get()->count();


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
            $podcast = Podcast::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('programa_id', $programa->id)->get()->count();
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
