<?php

namespace App\Models;

use App\Models\EstacionRadio;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'nivel',
        'estacion_radio_id',
        'estatus',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['nivel_nombre'];
    public function getnivelnombreAttribute() {
        if($this->nivel == 1 ) return 'Usuario';
        elseif($this->nivel == 2 ) return 'Gestion';
        elseif($this->nivel == 3 ) return 'Supervisor';
        elseif($this->nivel == 4 ) return 'Administrador';
        else  return "Sin Nivel";
    }
    public function estacion_radio()
    {
        return $this->hasOne(EstacionRadio::class, 'id', 'estacion_radio_id');
    }
}
