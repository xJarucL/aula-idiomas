<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'pk_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombres',
        'ap_paterno',
        'ap_materno',
        'matricula',
        'email',
        'password',
        'fk_tipo_usuario',
        'img_user'
    ];

    public function tipo_usuario(){
        return $this->belongsTo(TipoUsuario::class, 'fk_tipo_usuario', 'pk_tipo_usuario');
    }

    public function actividades(){
        return $this->hasMany(Actividades::class, 'fk_docente');
    }

    public function alumno(){
        return $this->belongsTo(Alumno::class, 'fk_usuario', 'pk_usuario');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
