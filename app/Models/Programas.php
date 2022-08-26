<?php

namespace App\Models;

use App\Models\Banner;
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
        'subtitulo',
        'contenido',
        'autor',
        'imagen_destacada',
        'imagen_banner',
        'estatus',
        'token',
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


    public function banner() {
        return $this->belongsTo(Banner::class);
    }

}
