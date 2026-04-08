<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table      = 'documentos';
    protected $primaryKey = 'id_documento';
    public    $incrementing = false;
    protected $keyType    = 'string';

    // Laravel usa created_at/updated_at por defecto — los mapeamos
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'ultima_modificacion';

    protected $fillable = [
        'id_usuario',
        'id_tipo',
        'nombre_documento',
        'numero_documento',
        'entidad_emisora',
        'pais_emision',
        'departamento_emision',
        'fecha_emision',
        'fecha_vencimiento',
        'storage_key',
        'nombre_archivo_original',
        'tamanio_bytes',
        'hash_sha256',
        'paginas',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_emision'     => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_creacion'    => 'datetime',
        'ultima_modificacion' => 'datetime',
    ];

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopePorTipo($query, int $idTipo)
    {
        return $query->where('id_tipo', $idTipo);
    }

    public function scopePorVencer($query, int $dias = 30)
    {
        return $query->whereNotNull('fecha_vencimiento')
                     ->whereBetween('fecha_vencimiento', [
                         now()->toDateString(),
                         now()->addDays($dias)->toDateString(),
                     ]);
    }

    public function scopeVencidos($query)
    {
        return $query->whereNotNull('fecha_vencimiento')
                     ->where('fecha_vencimiento', '<', now()->toDateString());
    }

    // -------------------------------------------------------
    // Accessors
    // -------------------------------------------------------

    // Calcula la vigencia en PHP (igual que la función SQL)
    public function getVigenciaAttribute(): string
    {
        if (!$this->fecha_vencimiento) {
            return 'vigente';
        }
        if ($this->fecha_vencimiento->isPast()) {
            return 'vencido';
        }
        if ($this->fecha_vencimiento->diffInDays(now()) <= 30) {
            return 'por_vencer';
        }
        return 'vigente';
    }

    // Tamaño legible: "1.2 MB"
    public function getTamanioLegibleAttribute(): string
    {
        $kb = $this->tamanio_bytes / 1024;
        if ($kb < 1024) {
            return round($kb, 1) . ' KB';
        }
        return round($kb / 1024, 1) . ' MB';
    }

    // -------------------------------------------------------
    // Relaciones
    // -------------------------------------------------------

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoDocumento::class, 'id_tipo', 'id_tipo');
    }

    public function historial()
    {
        return $this->hasMany(HistorialPdf::class, 'id_documento', 'id_documento')
                    ->orderBy('reemplazado_en', 'desc');
    }

    public function enlaces()
    {
        return $this->hasMany(EnlaceCompartido::class, 'id_documento', 'id_documento');
    }
}
