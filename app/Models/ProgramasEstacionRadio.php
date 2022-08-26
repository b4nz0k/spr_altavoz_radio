<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramasEstacionRadio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'programas_id',
        'estacion_radio_id',
    ];

    protected $table = 'programas_estacion_radio';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
}
