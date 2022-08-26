<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
