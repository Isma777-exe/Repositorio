<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPdf extends Model
{
    protected $table      = 'historial_pdf';
    protected $primaryKey = 'id_historial';
    public    $incrementing = false;
    protected $keyType    = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'id_documento',
        'storage_key',
        'nombre_archivo_original',
        'tamanio_bytes',
        'hash_sha256',
        'paginas',
        'reemplazado_por',
    ];

    protected $casts = [
        'reemplazado_en' => 'datetime',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }

    public function reemplazadoPorUsuario()
    {
        return $this->belongsTo(User::class, 'reemplazado_por', 'id_usuario');
    }
}
