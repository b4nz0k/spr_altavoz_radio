<?php

namespace App\Models\Gnrl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GnrlLogs extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'usuario_id', 
        'debug',
        'estatus',
        'getMessage',
        'getCode',
        'getLine',
        'getPath',
        'getFile',
    ];

    protected $table = 'gnrl_logs';

    protected $dates = ['deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y h:m:s',
    ];
}
