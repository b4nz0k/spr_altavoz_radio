<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Podcast;
use App\Models\Programas;
use App\Models\Categorias;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\FncGlobalesController;


class PodcastController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }
    public function programas() {
        // Listado de programas del mes podcast/programas
        return Podcast::programasMes();

    }
    public function search(Request $request) {
        try {
            // return response()->json($request->all());

            $this->validate($request, [
                'search' => 'required'
            ]);

            $se = $request->input('search');
            $busqueda = Podcast::where('titulo', 'like', '%'.$se.'%')
                                    ->orwhere('subtitulo', 'like', '%'.$se.'%');

            // return response()->json('asignando variable search');

            return $busqueda->paginate(8);
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }
    public function listado($estatus)
    {
        try {
            $listPodcast = [];
            $podcasts = '';
            if ($estatus == 'trash') {
                $est = 2;
            } elseif ($estatus == 'remove') {
                $est = 3;
            } elseif ($estatus == 'all') {
                $est = 1;
            }
            // Me costo esta consulta jaja
     $podcasts = DB::table('programas as P1')
                        ->select(
                            'C.id',
                            'C.titulo',
                            'C.subtitulo',
                            'C.usuario_id',
                            'C.productor',
                            'C.programas_id',
                            'C.token',
                            'C.estatus',
                            'C.created_at',
                            'P1.estacion_radio_id',
                            'P1.titulo as programa',
                            'D.nombre as productornombre'
                            )
     ->join('podcast as C', 'P1.id', '=', 'C.programas_id')
     ->join('productores as D', 'D.id', '=', 'C.productor')
      ->where('P1.estacion_radio_id', auth()->user()->estacion_radio_id)
      ->where('C.estatus', $est)
      ->orderByDesc('C.id')
     ->paginate(10);
     return response()->json($podcasts);

/*             $podcasts = Podcast::select(
                                        'id',
                                        'titulo',
                                        'subtitulo',
                                        'usuario_id',
                                        'productor',
                                        'programas_id',
                                        'estacion_radio_id',
                                        'token',
                                        'estatus',
                                        'created_at'
                                        )
                                    ->where('estatus', $est)->orderByDesc('id'); */

            foreach ($podcasts as $podcast) {
                $usuario = User::find($podcast->usuario_id);
                $estacion_radio = EstacionRadio::find($usuario->estacion_radio_id);
                $programa = Programas::find($podcast->programas_id);
                $categoria = Categorias::find($podcast->categoria_id) ?  Categorias::find($podcast->categoria_id) : 'Sin Categoria';
                // return $categoria;
                // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
                // $listEstacion = [];
                // foreach ($programas_estaciones as $programa_estacion) {
                //     $estacionRadio = EstacionRadio::find($programa_estacion->estacion_radio_id);
                //     array_push($listEstacion, [
                //         'estacion' => $estacionRadio->estacion,
                //     ]);
                // }

                array_push($listPodcast, [
                    'id'                => $podcast->id,
                    'titulo'            => $podcast->titulo,
                    'subtitulo'         => $podcast->subtitulo,
                    'usuario'           => $usuario->name,
                    'estacion'          => $estacion_radio->estacion,
                    'programa'          => $programa->titulo,
                    'categoria'         => $categoria,
                    'token'             => $podcast->token,
                    'estatus'           => $podcast->estatus,
                    'productor'         => $podcast->productornombre,
                    'name_estatus'      => PodcastController::estatus_podcast($podcast->estatus),
                    'created_at'        => Carbon::parse($podcast->created_at)->format('d-m-Y'),
                    'updated_at'        => Carbon::parse($podcast->updated_at)->format('d-m-Y'),
                ]);
            }

            return $listPodcast;

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
        return response()->json($request->all());
        try {
            $validator = \Validator::make($request->all(), [
                'titulo'            => ['required'],
                'subtitulo'         => ['required'],
                'contenido'         => ['required'],
                'imagen_destacada'  => ['required'],
                'programa'          => ['required'],
                'categoria'         => ['required'],
                'productor'         => ['required'],
                'audio'             => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'titulo'            => $validator->errors()->first('titulo'),
                    'subtitulo'         => $validator->errors()->first('subtitulo'),
                    'contenido'         => $validator->errors()->first('contenido'),
                    'imagen_destacada'  => $validator->errors()->first('imagen_destacada'),
                    'programa'          => $validator->errors()->first('programa'),
                    'categoria'         => $validator->errors()->first('categoria'),
                    'productor'         => $validator->errors()->first('productor'),
                    'audio'             => $validator->errors()->first('audio'),
                ]);
            }

            $podcast = new Podcast();
            $podcast->titulo               = $request->input('titulo');
            $podcast->subtitulo            = $request->input('subtitulo');
            $podcast->contenido            = $request->input('contenido');
            $podcast->estatus              = $request->input('estatus');
            $podcast->productor            = $request->input('productor');
            $podcast->programas_id         = $request->input('programa');
            $podcast->categoria_id         = $request->input('categoria');
            $podcast->usuario_id           = auth()->user()->id;
            $podcast->estacion_radio_id    = auth()->user()->estacion_radio_id;
            $podcast->token                = $this->funcion->token(20);
            $podcast->slug                 = Str::slug($request->input('titulo'));

            // return 'hola';
            if ($request->hasFile('imagen_destacada')) {
                $podcast->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            if ($request->hasFile('audio')) {
                $podcast->audio     =  $this->funcion->save_file($request->file('audio'), 'podcast' . 'programa');
            }

            $podcast->save();

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
                'programa'          => ['required'],
                'categoria'         => ['required'],
                'productor'         => ['required'],
                // 'audio'             => ['required', 'mimes:mp3'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'titulo'            => $validator->errors()->first('titulo'),
                    'subtitulo'         => $validator->errors()->first('subtitulo'),
                    'contenido'         => $validator->errors()->first('contenido'),
                    'programa'          => $validator->errors()->first('programa'),
                    'categoria'         => $validator->errors()->first('categoria'),
                    'productor'         => $validator->errors()->first('productor'),
                    // 'audio'         => $validator->errors()->first('audio'),
                ]);
            }
            // return 'hola';
            $buscarPodcast = Podcast::where('token', $request->input('token'))->first();

            $podcast = Podcast::find($buscarPodcast->id);
            $podcast->titulo               = $request->input('titulo');
            $podcast->subtitulo            = $request->input('subtitulo');
            $podcast->contenido            = $request->input('contenido');
            $podcast->estatus              = $request->input('estatus');
            $podcast->productor            = $request->input('productor');
            $podcast->programas_id          = $request->input('programa');
            $podcast->categoria_id         = $request->input('categoria');
            $podcast->usuario_id           = auth()->user()->id;
            $podcast->estacion_radio_id    = auth()->user()->estacion_radio_id;
            $podcast->token                = $this->funcion->token(20);
            $podcast->slug                 = Str::slug($request->input('titulo'));


            if ($request->hasFile('imagen_destacada')) {
                // return response()->json('Existe imagen');
                $podcast->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            if ($request->hasFile('audio')) {
                // return response()->json('Existe audio');
                $podcast->audio     =  $this->funcion->save_file($request->file('audio'), 'podcast' . 'programa');
            }

            $podcast->save();

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
            $buscarPodcast = Podcast::where('token', $request->input('token'))->first();
            $podcast = Podcast::find($buscarPodcast->id);
            $podcast->estatus = 3;
            $podcast->usuario_id = auth()->user()->id;
            $podcast->save();

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
            $buscarPodcast = Podcast::where('token', $request->input('token'))->first();
            $podcast = Podcast::find($buscarPodcast->id);
            $podcast->delete();

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
            $buscarPodcast = Podcast::where('token', $request->input('token'))->first();
            $podcast = Podcast::find($buscarPodcast->id);
            $podcast->estatus = 2;
            $podcast->usuario_id = auth()->user()->id;
            $podcast->save();


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
            $buscarPodcast = Podcast::where('token', $request->input('token'))->first();
            $podcast = Podcast::find($buscarPodcast->id);
            $podcast->estatus = 1;
            $podcast->usuario_id = auth()->user()->id;
            $podcast->save();

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
            $buscarPodcast = Podcast::where('token', $request->input('token'))->first();
            $podcast = Podcast::find($buscarPodcast->id);
            $podcast->estatus = 2;
            $podcast->usuario_id = auth()->user()->id;
            $podcast->save();

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
            $podcast = Podcast::where('token', $request->input('token'))->first();
            $listPodcast = [];

            //$programa_estacion = ProgramasEstacionRadio::where('programas_id', $programa->id)->where('estatus', $programa->estatus)->get();
            array_push($listPodcast, [
                'id'                => $podcast->id,
                'estacion_radio_id' => $podcast->estacion_radio_id,
                'usuario_id'        => $podcast->usuario_id,
                'programas'         => $podcast->programas_id,
                'categoria'         => $podcast->categoria_id,
                'titulo'            => $podcast->titulo,
                'subtitulo'         => $podcast->subtitulo,
                'contenido'         => $podcast->contenido,
                'productor'         => $podcast->productor,
                'imagen_destacada'  => $podcast->imagen_destacada,
                'audio'             => $podcast->audio,
                'estatus'           => $podcast->estatus,
            ]);

            return $listPodcast[0];
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
            $all = Podcast::where('estacion_radio_id',auth()->user()->estacion_radio_id)->whereIn('estatus', [1, 2])->get()->count();
            $publish = Podcast::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 1)->get()->count();
            $trash = Podcast::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 2)->get()->count();
            $delete = Podcast::where('estacion_radio_id', auth()->user()->estacion_radio_id)->where('estatus', 3)->get()->count();


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

    public function estatus_podcast($estatus)
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
