<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\User;
use App\Models\Programas;
use App\Models\Categorias;
use Illuminate\Http\Request;
use App\Models\EstacionRadio;
use App\Http\Controllers\FncGlobalesController;

class BlogController extends Controller
{
    protected $funcion;

    public function __construct()
    {
        $this->funcion = new FncGlobalesController();
    }

    public function listado($estatus)
    {
        try {
            $listBlog = [];
            $blogs = '';
            if ($estatus == 'trash') {
                $blogs = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 2)->orderByDesc('created_at')->get();
            } else if ($estatus == 'remove') {
                $blogs = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 3)->orderByDesc('created_at')->get();
            } else if ($estatus == 'all'){
                $blogs = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->orderByDesc('created_at')->get();
            }
            
            foreach ($blogs as $blog) {
                $usuario = User::find($blog->usuario_id);
                $estacion_radio = EstacionRadio::find($usuario->estacion_radio_id);
                $programa = Programas::find($blog->programas_id);
                $categoria = Categorias::find($blog->categoria_id);

                // $programas_estaciones = ProgramasEstacionRadio::where('programas_id', $programa->id)->get();
                // $listEstacion = [];
                // foreach ($programas_estaciones as $programa_estacion) {
                //     $estacionRadio = EstacionRadio::find($programa_estacion->estacion_radio_id);
                //     array_push($listEstacion, [
                //         'estacion' => $estacionRadio->estacion,
                //     ]);
                // }
                array_push($listBlog, [
                    'id'                => $blog->id,
                    'titulo'            => $blog->titulo,
                    'subtitulo'         => $blog->subtitulo,
                    'usuario'           => $usuario->name,
                    'estacion'          => $estacion_radio->estacion,
                    'programa'          => $programa->titulo,
                    'categoria'         => $categoria->categoria,
                    'token'             => $blog->token,
                    'estatus'           => $blog->estatus,
                    'name_estatus'      => BlogController::estatus_blog($blog->estatus),
                    'created_at'        => Carbon::parse($blog->created_at)->format('d-m-Y'),
                    'updated_at'        => Carbon::parse($blog->updated_at)->format('d-m-Y'),
                ]);
            }

            return $listBlog;

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
                'contenido'         => ['required'],
                'imagen_destacada'  => ['required'],
                'programa'          => ['required'],
                'categoria'         => ['required'],
                'productor'         => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'answer'            => false,
                    'titulo'            => $validator->errors()->first('titulo'),
                    'contenido'         => $validator->errors()->first('contenido'),
                    'imagen_destacada'  => $validator->errors()->first('imagen_destacada'),
                    'programa'          => $validator->errors()->first('programa'),
                    'categoria'         => $validator->errors()->first('categoria'),
                    'productor'         => $validator->errors()->first('productor'),
                ]);
            }

            $blog = new Blog();
            $blog->titulo               = $request->input('titulo');
            $blog->subtitulo            = $request->input('subtitulo');
            $blog->contenido            = $request->input('contenido');
            $blog->estatus              = $request->input('estatus');
            $blog->productor            = $request->input('productor');
            $blog->programas_id          = $request->input('programa');
            $blog->categoria_id         = $request->input('categoria');
            $blog->usuario_id           = \Auth::user()->id;
            $blog->estacion_radio_id    = \Auth::user()->estacion_radio_id;
            $blog->token                = $this->funcion->token(20);
            
            if ($request->hasFile('imagen_destacada')) {
                $blog->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            $blog->save();

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
                ]);
            }

            $buscarBlog = Blog::where('token', $request->input('token'))->first();

            $blog = Blog::find($buscarBlog->id);
            $blog->titulo               = $request->input('titulo');
            $blog->subtitulo            = $request->input('subtitulo');
            $blog->contenido            = $request->input('contenido');
            $blog->estatus              = $request->input('estatus');
            $blog->productor            = $request->input('productor');
            $blog->programas_id          = $request->input('programa');
            $blog->categoria_id         = $request->input('categoria');
            $blog->usuario_id           = \Auth::user()->id;
            $blog->estacion_radio_id    = \Auth::user()->estacion_radio_id;
            $blog->token                = $this->funcion->token(20);
            
            if ($request->hasFile('imagen_destacada')) {
                $blog->imagen_destacada     =  $this->funcion->save_file($request->file('imagen_destacada'), 'upload');
            }

            $blog->save();

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
            $buscarBlog = Blog::where('token', $request->input('token'))->first();
            $blog = Blog::find($buscarBlog->id);
            $blog->estatus = 3;
            $blog->usuario_id = \Auth::user()->id;
            $blog->save();

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
            $buscarBlog = Blog::where('token', $request->input('token'))->first();
            $blog = Blog::find($buscarBlog->id);
            $blog->delete();

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
            $buscarBlog = Blog::where('token', $request->input('token'))->first();
            $blog = Blog::find($buscarBlog->id);
            $blog->estatus = 2;
            $blog->usuario_id = \Auth::user()->id;
            $blog->save();
            

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
            $buscarBlog = Blog::where('token', $request->input('token'))->first();
            $blog = Blog::find($buscarBlog->id);
            $blog->estatus = 1;
            $blog->usuario_id = \Auth::user()->id;
            $blog->save();

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
            $buscarBlog = Blog::where('token', $request->input('token'))->first();
            $blog = Blog::find($buscarBlog->id);
            $blog->estatus = 2;
            $blog->usuario_id = \Auth::user()->id;
            $blog->save();

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
            $blog = Blog::where('token', $request->input('token'))->first();
            $listBlog = [];

            array_push($listBlog, [
                'id'                => $blog->id,
                'estacion_radio_id' => $blog->estacion_radio_id,
                'usuario_id'        => $blog->usuario_id,
                'programas'         => $blog->programas_id,
                'categoria'         => $blog->categoria_id,
                'titulo'            => $blog->titulo,
                'subtitulo'         => $blog->subtitulo,
                'contenido'         => $blog->contenido,
                'productor'         => $blog->productor,
                'imagen_destacada'  => $blog->imagen_destacada,
                'estatus'           => $blog->estatus,
            ]);

            return $listBlog[0];
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
            $all = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->whereIn('estatus', [1, 2])->get()->count();
            $publish = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 1)->get()->count();
            $trash = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 2)->get()->count();
            $delete = Blog::where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('estatus', 3)->get()->count();

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

    public function estatus_blog($estatus)
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
