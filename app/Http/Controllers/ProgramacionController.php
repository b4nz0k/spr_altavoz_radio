<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\progTemp;
use App\Models\Programacion;
use Illuminate\Http\Request;

class ProgramacionController extends Controller
{
    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function index($dia)
    {
        try {
            return Programacion::select(
                                    'id',
                                    'programa_id',
                                    'dia',
                                    'tipo_transmision',
                                    'hora_inicio',
                                    'hora_final',
                                )
                                ->whereDate('created_at', '>=', now()->subDays(25))
                                ->get();
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }

    public function list($page) {
        try {
            // Filtros de rangos de fecha por pagina - A la Semana
            if ($page >= 0) {
                $dia = ($page == 1 || $page == 0 ) ? ( 0 ) : ($page * 7 - 7);
            }
            elseif ($page < 0) {
                $dia = ($page == -1 ) ? ( -7 ) : ($page * 7 + 7);
            }
            // Consulta dependiendo los rangos de fecha - A la semana
            $en = Carbon::now()->add($dia, 'day');
            $start = $en->startOfWeek()->sub(1, 'day')->format('Y-m-d H:i');
            $end = $en->endOfWeek(-1)->format('Y-m-d H:i');

            // return $start . '<br/>' . $end;
            $arreglo = [];
            $programacion = Programacion::select(
                                            'id',
                                            'programa_id',
                                            'dia',
                                            'color',
                                            'tipo_transmision',
                                            'hora_inicio',
                                            'hora_final',
                                        )->whereDate('hora_inicio', '>=', $start)
                                        ->whereDate('hora_final', '<=', $end)
                                        ->get();
            // return 'Hasta aqui va bien';

            foreach($programacion as $item) {
                if ($item->color == null) {
                    $item->color = '#2B47FFFF';
                }
                if ($dia = Carbon::parse($item->hora_inicio))
                $dia = $dia->isoFormat('dddd');

                // if (in_array(auth()->user()->estacion_radio_id, $item->setaciones_radio)) {
                    array_push($arreglo, [
                        'id'                => $item->id,
                        'programa_id'       => $item->programa_id,
                        'tipo_transmision'  => $item->tipo_transmision,
                        'start'             => $item->hora_inicio,
                        'end'               => $item->hora_final,
                        'hr_inicio'         => $item->hr_inicio,
                        'hr_final'          => $item->hr_final,
                        'color'             => $item->color,
                        'dia'               => $dia,
                        'name'              => $item->name,
                    ]);
                // }
                // return 'hola';
                // $arreglo[$item->id]->id = $item->id;
            }
            // array_push($arreglo->, rango = substr($item->hora_inicio, 0,10). ' a ' .substr($item->hora_final,0,10);
            return response()->json([
                'datos' => $arreglo,
                'rango' => substr($start, 0,10). ' a ' .substr($end,0,10),
                'start' => substr($start, 0,10)
            ]
                );

        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);
        }
    }


    public function insertar(Request $request)
    {
        $agregar = new Programacion();
        $agregar->id_programa = $request->input("id_programa");
        $agregar->inicio = $request->input("inicio");
        $agregar->final= $request->input("fin");
        $agregar->id_estacion=1;
        $agregar->id_podcast = ($request->input("id_podcast")) ? $request->input("id_podcast") : null;
        $agregar->id_tipo = 1;
        $agregar->save();
        //eliminar temporal
        if($request->input("id_temp")>0){
                    $temporal = progTemp::find($request->input("id_temp"));
                    $temporal->forceDelete();
        }

        return $agregar->id;

    }

    public function store(Request $request)
    {
        try {
            // return response()->json([
            //     'msj' => 'Entrando al store',
            //     'request' => $request->all()
            // ]);
            $this->validate($request, [
                'programa_id'       => 'required',
                'tipo_transmision'  => 'required',
                'hora_inicio'       => 'required',
                'hora_final'        => 'required',
            ]);
            // return response()->json([
            //     'msj' => 'Pasando validacion',
            //     'request' => $request->all()
            // ]);
            $programacion = new Programacion();
            $programacion->estaciones_radio_id  =   auth()->user()->estacion_radio_id;
            $programacion->programa_id          =   $request->input('programa_id');
            $programacion->tipo_transmision     =   $request->input('tipo_transmision');
            $programacion->hora_inicio          =   $request->input('hora_inicio');
            $programacion->hora_final           =   $request->input('hora_final');

            $programacion->user_id              =   auth()->user()->id;
            $programacion->save();
            // return response()->json('creacio de proramacion');

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
            ]);        }
    }

    public function update(Request $request)
    {
        // return response()->json($request->all());

        try {

            $this->validate($request, [
                'programa_id'       => 'required',
                'tipo_transmision'  => 'required',
                'hora_inicio'       => 'required',
                'hora_final'        => 'required',
            ]);
            // return response()->json([
            //     'msj' => 'Pasando validacion',
            //     'request' => $request->all()
            // ]);
            $programacion = Programacion::find($request['id']);
            $programacion->programa_id          =   $request->input('programa_id');
            $programacion->tipo_transmision     =   $request->input('tipo_transmision');
            $programacion->hora_inicio          =   $request->input('hora_inicio');
            $programacion->hora_final           =   $request->input('hora_final');
            $programacion->color                =   $request->input('color');
            $programacion->user_id              =   auth()->user()->id;
            $programacion->save();
            // return response()->json('creacio de proramacion');

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
            ]);        }    }
    public function edit($id) {
        try {
            // return response()->json('Accediendo a edit');
            $programacion = Programacion::find($id);
            return response()->json($programacion);
        } catch (\Throwable $th) {
            $this->funcion->logs( 'Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
            return response()->json([
                'answer' => false,
                'msg' => 'Algo ha salido mal, inténtalo de nuevo.',
                'php'    => $th->getMessage(),
            ]);        }
    }

    public function lista()
    {
        $listaPodcast = \DB::table('programacion')
        ->join('programas', 'programas.id', '=', 'programacion.id_programa')
        ->join('podcast', 'programacion.id_podcast', '=', 'podcast.id')
        ->select('programacion.id_estacion', 'programacion.id_programa', 'programacion.final', 'programacion.inicio', 'programacion.id_podcast', 'programas.titulo', 'podcast.titulo as podcastº  ')
        ->get();

        $listaSinPodcast = \DB::table('programacion')
        ->join('programas', 'programas.id', '=', 'programacion.id_programa')
        ->select('programacion.id_estacion', 'programacion.id_programa', 'programacion.final', 'programacion.inicio', 'programas.titulo',)
        ->get();

        $regreso = array();

        foreach ($listaPodcast as $elemento) {
            array_push($regreso, array(
                "id_estacion"=>$elemento->id_estacion,
                "id_programa"=>$elemento->id_programa,
                "final"=>$elemento->final,
                "inicio"=>$elemento->inicio,
                "id_podcast"=>$elemento->id_podcast,
                "titulo"=>$elemento->titulo,
                // "podcast"=>$podcast
            ));
        }



        foreach ($listaSinPodcast as $elemento) {
            array_push($regreso, array(
                "id_estacion"=>$elemento->id_estacion,
                "id_programa"=>$elemento->id_programa,
                "final"=>$elemento->final,
                "inicio"=>$elemento->inicio,
                "id_podcast"=>0,
                "titulo"=>$elemento->titulo,
                "podcast"=>0
            ));
        }




        return $regreso;
    }



    public function destroy($id)
    {
        // return response()->json('accediendo al destroy');
        try {
            $programacion = Programacion::find($id);
            $programacion->delete();
            return response()->json([
                'answer' => true,
                'msg' => 'El registro se ha eliminado exitosamente.',
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
