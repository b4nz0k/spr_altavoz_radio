<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Multimedia extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estacion_radio_id',
        'usuario_id',
        'programas_id',
        'categoria_id',
        'titulo',
        'subtitulo',
        'contenido',
        'imagen_destacada',
        'video_upload',
        'video_iframe',
        'productor',
        'estatus',
        'token',
    ];

    protected $table = 'multimedia';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
}
