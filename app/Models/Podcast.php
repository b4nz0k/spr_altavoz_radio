<?php

namespace App\Models;

use App\Models\Programas;
use App\Models\Productores;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Podcast extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estacion_radio_id',
        'usuario_id',
        'categoria_id',
        'programas_id',
        'titulo',
        'subtitulo',
        'slug',
        'contenido',
        'productor',
        'imagen_destacada',
        'audio',
        'estatus',
        'token',
    ];

    protected $table = 'podcast';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['productornombre', 'name_estatus', 'programa', 'estacion'];

    public function getnameestatusAttribute() {
        if($this->estatus)
            return $this->estatus_programa($this->estatus);
        return "Sin Estatus";
    }

    public function getprogramaAttribute() {
        if($this->programas_id)
            return Programas::find($this->programas_id)->titulo;
        return "Sin Programa";
    }
    public function getestacionAttribute() {
        if($this->programas_id)
            return Programas::find($this->programas_id)->estacion_radio_id;
        return "Sin Estacion";
    }
    public function getproductornombreAttribute() {
        if($productor = Productores::find($this->productor)) {
            return $productor->nombre;
        }
        else {
            return "Sin productor";
        }
    }
    public static function programasMes() {
        $datos = Programas::whereDate('created_at', '>=', now()->subDays(30))
                            ->where('estatus',1)
                            ->orderByDesc('created_at')
                            ->get();
        return $datos;
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
    public function estacion_c() {
        return $this->hasmany(Programas::class, 'programas_id');
            // ->select('id', 'estacion_radio_id')
            // ->where('programas.estacion_radio_id', auth()->user()->estacion_radio_id);
    }

}
