<?php

namespace App\Models;

use App\Models\Programas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Symfony\Component\VarDumper\Cloner\Data;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'programas_id',
        'status',
        'estacion_id',
        'fecha_inicio',
        'fecha_fin',
        'link',
    ];
    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    protected $table = 'banner';

    protected $appends = ['programa_titulo', 'programa_subtitulo', 'programa_banner', 'programa_destacado', 'estaciones_lista'];

    public function getprogramatituloAttribute() {
        if ($this->programas_id)
            return Programas::find($this->programas_id)->titulo;
        else
            return 'Sin Titulo';
    }
    public function getprogramasubtituloAttribute() {
        if ($this->programas_id)
            return Programas::find($this->programas_id)->subtitulo;
        else
            return 'Sin subtitulo';

    }
    public function getprogramabannerAttribute() {
        if ($this->programas_id)
            // return Programas::find($this->programas_id)->imagen_banner;
            return Programas::find($this->programas_id)->imagen_destacada;
        else
            return 'Sin Imagen';
    }
    public function getprogramadestacadoAttribute() {
        if ($this->programas_id)
            return Programas::find($this->programas_id)->imagen_banner;
            // return Programas::find($this->programas_id)->imagen_destacada;
        else
            return 'Sin Imagen';
    }
    public function getestacioneslistaAttribute() {
        if ($this->programas_ids)
            return Programas::find($this->programas_ids)->estacioneslista;
    }

    public static function programasMes() {
        $datos = Programas::whereDate('created_at', '>=', now()->subDays(30) )->get();
        // $datos = Programas::whereBetween('created_at', [now()->subDays(30), today() ])->get();
        return $datos;
    }
    public static function programasTodos() {
        return Programas::all();
    }

}
