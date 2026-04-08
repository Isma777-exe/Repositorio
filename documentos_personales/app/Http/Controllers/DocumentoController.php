<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    // Formulario de subida
    public function create(int $idTipo)
    {
        $tipo = TipoDocumento::activos()->findOrFail($idTipo);
        return view('documentos.create', compact('tipo'));
    }

    // Guardar nuevo documento PDF
    public function store(Request $request)
    {
        $tipo = TipoDocumento::findOrFail($request->id_tipo);

        $rules = [
            'id_tipo'           => 'required|integer|exists:tipos_documento,id_tipo',
            'nombre_documento'  => 'required|string|max:200',
            'archivo_pdf'       => 'required|file|mimes:pdf|max:20480',
            'fecha_emision'     => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date',
            'notas'             => 'nullable|string|max:1000',
        ];

        if ($tipo->numero_requerido) {
            $rules['numero_documento'] = 'required|string|max:100';
        }
        if ($tipo->emisor_requerido) {
            $rules['entidad_emisora'] = 'required|string|max:200';
        }

        $request->validate($rules);

        $archivo = $request->file('archivo_pdf');
        $hash    = hash_file('sha256', $archivo->getRealPath());

        // Verificar duplicado para este usuario
        $duplicado = Documento::where('id_usuario', Auth::user()->id_usuario)
            ->where('hash_sha256', $hash)
            ->where('estado', '!=', 'eliminado')
            ->exists();

        if ($duplicado) {
            return back()->withErrors(['archivo_pdf' => 'Ya tienes este PDF subido en tu folder.']);
        }

        // Guardar el PDF en storage/app/documentos/
        $nombreArchivo = Str::uuid() . '.pdf';
        $carpeta       = Auth::user()->id_usuario;
        $archivo->storeAs($carpeta, $nombreArchivo, 'documentos');
        $storageKey = $carpeta . '/' . $nombreArchivo;

        Documento::create([
            'id_documento'            => Str::uuid(),
            'id_usuario'              => Auth::user()->id_usuario,
            'id_tipo'                 => $request->id_tipo,
            'nombre_documento'        => $request->nombre_documento,
            'numero_documento'        => $request->numero_documento,
            'entidad_emisora'         => $request->entidad_emisora,
            'pais_emision'            => $request->pais_emision,
            'departamento_emision'    => $request->departamento_emision,
            'fecha_emision'           => $request->fecha_emision,
            'fecha_vencimiento'       => $request->fecha_vencimiento,
            'storage_key'             => $storageKey,
            'nombre_archivo_original' => $archivo->getClientOriginalName(),
            'tamanio_bytes'           => $archivo->getSize(),
            'hash_sha256'             => $hash,
            'notas'                   => $request->notas,
            'estado'                  => 'activo',
        ]);

        return redirect()
            ->route('folder.categoria', $request->id_tipo)
            ->with('success', 'Documento subido correctamente.');
    }

    // Ver PDF en el navegador (inline)
    public function ver(string $idDocumento)
    {
        $documento = $this->autorizar($idDocumento);

        $contenido = Storage::disk('documentos')->get($documento->storage_key);

        return response($contenido, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $documento->nombre_archivo_original . '"');
    }

    // Descargar PDF
    public function descargar(string $idDocumento)
    {
        $documento = $this->autorizar($idDocumento);

        return Storage::disk('documentos')->download(
            $documento->storage_key,
            $documento->nombre_archivo_original
        );
    }

    // Reemplazar PDF (el trigger de PostgreSQL archiva el anterior)
    public function reemplazar(Request $request, string $idDocumento)
    {
        $documento = $this->autorizar($idDocumento);

        $request->validate([
            'archivo_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $archivo = $request->file('archivo_pdf');
        $hash    = hash_file('sha256', $archivo->getRealPath());

        if ($documento->hash_sha256 === $hash) {
            return back()->withErrors(['archivo_pdf' => 'El PDF es idéntico al actual.']);
        }

        $nombreArchivo = Str::uuid() . '.pdf';
        $carpeta       = Auth::user()->id_usuario;
        $archivo->storeAs($carpeta, $nombreArchivo, 'documentos');
        $nuevoKey = $carpeta . '/' . $nombreArchivo;

        $documento->update([
            'storage_key'             => $nuevoKey,
            'nombre_archivo_original' => $archivo->getClientOriginalName(),
            'tamanio_bytes'           => $archivo->getSize(),
            'hash_sha256'             => $hash,
        ]);

        return back()->with('success', 'PDF reemplazado correctamente.');
    }

    // Mover a papelera
    public function papelera(string $idDocumento)
    {
        $documento = $this->autorizar($idDocumento);
        $idTipo    = $documento->id_tipo;
        $documento->update(['estado' => 'papelera']);

        return redirect()
            ->route('folder.categoria', $idTipo)
            ->with('success', 'Documento movido a la papelera.');
    }

    // Verifica que el documento pertenezca al usuario autenticado
    private function autorizar(string $id): Documento
    {
        return Documento::where('id_documento', $id)
            ->where('id_usuario', Auth::user()->id_usuario)
            ->where('estado', 'activo')
            ->firstOrFail();
    }
}