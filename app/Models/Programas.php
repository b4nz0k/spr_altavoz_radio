<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Banner;
use App\Models\Autores;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programas extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estacion_radio_id',
        'usuario_id',
        'titulo',

        'slug',

        'subtitulo',
        'contenido',
        'autor',
        'imagen_destacada',
        'imagen_banner',
        'estatus',

        'podcasts',

        'token',
        'estaciones_ids',
    ];
    protected $hidden = [
        'updated_at',
        'created_at'
    ];
    protected $table = 'programas';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
    // 'selectestaciones', 'estacioneslista',
    protected $appends = [ 'autornombre', 'name_estatus', 'creacion'];

    public function getcreacionAttribute() {
        if($this->created_at)
            return Carbon::parse($this->created_at)->format('d-m-Y');
        return "Sin Fecha";
    }
    public function getnameestatusAttribute() {
        if($this->estatus)
            return $this->estatus_programa($this->estatus);
        return "Sin Estatus";
    }
    public function getautornombreAttribute() {
        if($this->autor)
            return Autores::find($this->autor)->autor;
        return "Sin autor";
    }
/*     public function getselectestacionesAttribute() {
        $array = array_map('intval', explode( ',', $this->estaciones_ids ) );
        return $array ;
    }
    public function getestacioneslistaAttribute() {
        $array = array_map('intval', explode( ',', $this->estaciones_ids ) );
        return $array;
    } */


    public function banner() {
        return $this->belongsTo(Banner::class);
    }

    public function podcastpro() {
        return $this->hasmany(Podcast::class, 'programas_id');
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
