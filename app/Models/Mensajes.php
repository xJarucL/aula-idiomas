<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensajes extends Model
{

    protected $table = 'mensajes';
    protected $primaryKey = 'pk_mensaje';

    protected $fillable = [
        'de_usuario',
        'para_usuario',
        'mensaje'
    ];

    public function deUsuario(){
        return $this->belongsTo(User::class, 'de_usuario');
    }

    public function paraUsuario(){
        return $this->belongsTo(User::class, 'para_usuario');
    }
}
