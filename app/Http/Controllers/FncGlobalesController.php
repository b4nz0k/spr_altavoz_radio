<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UploadFiles;
use Illuminate\Http\Request;
use App\Models\Gnrl\GnrlLogs;
use App\Models\Gnrl\GnrlMovimientos;

class FncGlobalesController extends Controller
{

    public function logs($debug, $estatus, $getMessage, $getCode, $getLine, $getFile)
    {
        try {
            $logs = new GnrlLogs();
            $explodeFile = explode('\\', $getFile);
            $logs->usuario_id   = \Auth::user()->id;
            $logs->debug        = $debug;
            $logs->estatus      = $estatus;
            $logs->getMessage   = $getMessage;
            $logs->getCode      = $getCode;
            $logs->getLine      = $getLine;
            $logs->getPath      = $getFile;
            $logs->getFile      = $explodeFile[count($explodeFile) - 1];
            $logs->ip_address   = \Request::ip();
            $logs->save();
        } catch (\Throwable $th) {
            FncGlobalesController::logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
        }
    }

    public function logsFronted(Request $request)
    {
        try {
            FncGlobalesController::logs($request->input('debug'), $request->input('estatus'), $request->input('menssage'), null, null, $request->input('file'));
        } catch (\Throwable $th) {
            FncGlobalesController::logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
        }
    }

    public function showLogs()
    {
        try {
            $logs = GnrlLogs::orderBy('id', 'DESC')->get();
            $listLogs = [];
            foreach ($logs as $log) {
                $usuario = User::where('id', $log->usuario_id)->first();
                array_push($listLogs, [
                    'id'            => $log->id,
                    'debug'         => $log->debug,
                    'estatus'       => $log->estatus,
                    'getMessage'    => $log->getMessage,
                    'getCode'       => $log->getCode,
                    'getLine'       => $log->getLine,
                    'getFile'       => $log->getFile,
                    'usuario'       => $usuario->name,
                    'ipAddress'     => $log->ip_address,
                    'fecha'         => Carbon::parse($log->created_at)->format('d-m-Y H:m:s'),
                ]);
            }
            return $listLogs;
        } catch (\Throwable $th) {
            FncGlobalesController::logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
        }
    }

    public function token ($longitud)
    {
        if ($longitud < 4) {
            $longitud = 4;
        }

        return bin2hex(random_bytes(($longitud - ($longitud % 2)) / 2));
    }

    public function save_file($file, $path)
    {
        try {
            $nombre_encriptado = md5($file->getClientOriginalName() . Carbon::now()->toDateTimeString());
            $uploadFiles = new UploadFiles();
            $uploadFiles->estacion_radio_id = \Auth::user()->estacion_radio_id;
            $uploadFiles->file_name_original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $uploadFiles->file_name_renombrado = $nombre_encriptado;
            $uploadFiles->file_size = $file->getsize();
            $uploadFiles->file_extension = $file->getClientOriginalExtension();
            if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg') {
                $uploadFiles->file_tipo = 'imagen';
            } else {
                $uploadFiles->file_tipo = 'audio';
            }

            $uploadFiles->save();
            $file->move($path, $nombre_encriptado . '.' . $file->getClientOriginalExtension());
            return '/' . $path . '/' . $nombre_encriptado . '.' . $file->getClientOriginalExtension();

            // $uploadFiles->save();
            // $file->move('podcast', $nombre_encriptado . '.' . $file->getClientOriginalExtension());
            // return '/upload/' . $nombre_encriptado . '.' . $file->getClientOriginalExtension();

        } catch (\Throwable $th) {
            FncGlobalesController::logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
        }
    }

    public function create_slug($string)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return $slug;
    }

    public function galeria($file_tipo)
    {
        try {
            return UploadFiles::select('id', 'estacion_radio_id', 'file_name_original', 'file_name_renombrado', 'file_size', 'file_extension', 'file_tipo')
            ->where('estacion_radio_id', \Auth::user()->estacion_radio_id)->where('file_tipo', $file_tipo)->get();
        } catch (\Throwable $th) {
            FncGlobalesController::logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
        }
    }

    public function ckeditorImagen(Request $request)
    {
        try {
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');

                $nombre_encriptado = md5($file->getClientOriginalName() . Carbon::now()->toDateTimeString());
                $uploadFiles = new UploadFiles();
                $uploadFiles->estacion_radio_id = \Auth::user()->estacion_radio_id;
                $uploadFiles->file_name_original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $uploadFiles->file_name_renombrado = $nombre_encriptado;
                $uploadFiles->file_size = $file->getsize();
                $uploadFiles->file_extension = $file->getClientOriginalExtension();
                if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg') {
                    $uploadFiles->file_tipo = 'imagen';
                } else {
                    $uploadFiles->file_tipo = 'audio';
                }

                $uploadFiles->save();
                $file->move('upload', $nombre_encriptado . '.' . $file->getClientOriginalExtension());
                return '/upload/' . $nombre_encriptado . '.' . $file->getClientOriginalExtension();
            }
        } catch (\Throwable $th) {
            FncGlobalesController::logs('Backend', '500', $th->getMessage(), $th->getCode(), $th->getLine(), $th->getFile());
        }
    }
}
