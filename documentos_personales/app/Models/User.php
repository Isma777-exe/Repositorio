<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public    $incrementing = false;
    protected $keyType    = 'string';
    const CREATED_AT = 'fecha_registro';
    const UPDATED_AT = null;  // ← null porque no tenemos updated_at

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password_hash',
        'rol',
        'estado',
        'pais_defecto',
        'zona_horaria',
    ];

    protected $hidden = ['password_hash'];

    protected $casts = [
        'fecha_registro'  => 'datetime',
        'ultimo_acceso'   => 'datetime',
        'bloqueado_hasta' => 'datetime',
    ];

    // Laravel usa 'password' internamente — apuntamos a nuestro campo
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // -------------------------------------------------------
    // Helpers de rol
    // -------------------------------------------------------

    public function esAdmin(): bool
    {
        return $this->rol === 'administrador';
    }

    public function esUsuario(): bool
    {
        return $this->rol === 'usuario';
    }

    // -------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_usuario', 'id_usuario')
                    ->where('estado', 'activo')
                    ->orderBy('fecha_creacion', 'desc');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'id_usuario', 'id_usuario');
    }
}
