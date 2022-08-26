<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UploadFiles extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'file_name_original',
        'file_name_renombrado',
        'file_size',
        'file_extension',
        'file_tipo',
    ];

    protected $table = 'upload_files';

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    protected $dates = ['deleted_at'];
}
