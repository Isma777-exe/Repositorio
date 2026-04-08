<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table      = 'tipos_documento';
    protected $primaryKey = 'id_tipo';
    public    $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tiene_vencimiento',
        'numero_requerido',
        'emisor_requerido',
        'activo',
    ];

    protected $casts = [
        'tiene_vencimiento' => 'boolean',
        'numero_requerido'  => 'boolean',
        'emisor_requerido'  => 'boolean',
        'activo'            => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'id_tipo', 'id_tipo');
    }
}
