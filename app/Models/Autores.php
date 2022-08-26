<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Autores extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'usuario_id',
        'autor',
        'token',
    ];

    protected $table = 'autores';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
}
