<?php

namespace App\Http\Controllers;

use App\Models\progTemp;
use App\Models\Programacion;
use Illuminate\Http\Request;

class ProgramacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Programacion  $programacion
     * @return \Illuminate\Http\Response
     */
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
                "podcast"=>$podcast
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Programacion  $programacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Programacion $programacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Programacion  $programacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programacion $programacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Programacion  $programacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programacion $programacion)
    {
        //
    }
}