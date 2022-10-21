<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstacionRadio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'estacion',
        'descripcion',
    ];

    protected $table = 'estacion_radio2';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
}
