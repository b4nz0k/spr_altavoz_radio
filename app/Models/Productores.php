<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Productores extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'productores';

    protected $fillable = [
        'id',
        'nombre',
        'user_id',
        'estacion_radio_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];

}
