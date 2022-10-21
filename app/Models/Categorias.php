<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorias extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'estacion_radio_id',
        'categoria',
        'descripcion',
        'slug',
        'tipo',
        'estatus',
        'token',
    ];

    protected $table = 'categorias';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
}
