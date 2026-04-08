<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    // Dashboard: resumen del folder del usuario
    public function index()
    {
        $usuario = Auth::user();

        $tipos = TipoDocumento::activos()
            ->withCount([
                'documentos as total' => fn($q) =>
                    $q->where('id_usuario', $usuario->id_usuario)
                      ->where('estado', 'activo')
            ])
            ->get();

        $porVencer = Documento::where('id_usuario', $usuario->id_usuario)
            ->activos()
            ->porVencer(30)
            ->with('tipo')
            ->get();

        $vencidos = Documento::where('id_usuario', $usuario->id_usuario)
            ->activos()
            ->vencidos()
            ->count();

        $totalDocumentos = Documento::where('id_usuario', $usuario->id_usuario)
            ->activos()
            ->count();

        return view('folder.index', compact('tipos', 'porVencer', 'vencidos', 'totalDocumentos'));
    }

    // Documentos de una categoría específica
    public function categoria(int $idTipo)
    {
        $usuario = Auth::user();

        $tipo = TipoDocumento::activos()->findOrFail($idTipo);

        $documentos = Documento::where('id_usuario', $usuario->id_usuario)
            ->activos()
            ->porTipo($idTipo)
            ->with('tipo')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        return view('folder.categoria', compact('tipo', 'documentos'));
    }

    // Documentos en la papelera
    public function papelera()
    {
        $usuario = Auth::user();

        $documentos = Documento::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'papelera')
            ->with('tipo')
            ->orderBy('ultima_modificacion', 'desc')
            ->get();

        return view('folder.papelera', compact('documentos'));
    }
}