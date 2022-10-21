<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Programas;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Programacion extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $table = 'programacion';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'estacion_radio_id',
        'programa_id',
        'dia',
        'tipo_transmision',
        'hora_inicio',
        'hora_final',
        'created_at'
    ];
    //  'estaciones_radio',
    protected $appends = ['name', 'dia_string', 'hr_inicio', 'hr_final'];


    public function getnameAttribute() {
        $programa = Programas::find($this->programa_id)->titulo;
        return $programa;
    }

/*     public function getestacionesradioAttribute() {
        // $estaciones = Programas::find($this->programa_id)->estaciones_ids;
        $estaciones = array_map('intval', explode( ',', Programas::find($this->programa_id)->estaciones_ids ) );
        return $estaciones;
    } */

    public function getdiastringAttribute() {
        // Carbon::parse($article->expired_at)->format('Y-m-d')
        if ($dia = Carbon::parse($this->hora_inicio))
            return $dia->isoFormat('dddd');
        return 'Sin Dia';

    }

    public function gethrinicioAttribute() {
        // $hora = array_map('intval', explode( ' ', $this->hora_inicio ) );
        $hora = Carbon::parse($this->hora_inicio);
        return $hora->format('H:i:s');
    }
    public function gethrfinalAttribute() {
        $hora = Carbon::parse($this->hora_final);
        return $hora->format('H:i:s');
        // return $hora->isoFormat('h:mm');
    }
}
