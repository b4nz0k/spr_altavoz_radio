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

    protected $appends = ['programa_titulo', 'programa_subtitulo', 'programa_banner'];

    public function getprogramatituloAttribute() {
        return Programas::find($this->programas_id)->titulo;
    }
    public function getprogramasubtituloAttribute() {
        return Programas::find($this->programas_id)->subtitulo;
    }
    public function getprogramabannerAttribute() {
        return Programas::find($this->programas_id)->imagen_banner;
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
