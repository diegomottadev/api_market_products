<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    const USUARIO_VERIFICADO = 1;
    const USUARIO_NO_VERIFICADO = 2;

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $table = 'users';

    protected $fillable = [
        'name',
         'email', 
         'password',
         'verified',
        'verification_token',
        'admin'
    ];


    public function setNameAttribute($valor){
        return $this->attributes['name']= strtolower($valor);

    }


    public function getNameAttribute($valor){
        return ucwords($valor);

    }

    public function setEmailAttribute($valor){
        return $this->attributes['email']= strtolower($valor);
        
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function esVerificado(){
        return $this->verified == USER::USUARIO_VERIFICADO;
    }

    public function esAdministrador(){
        return $this->verified == USER::USUARIO_NO_VERIFICADO;
    }

    public static function generarVerificationToken(){
        return Str::random(40);
    }

}
