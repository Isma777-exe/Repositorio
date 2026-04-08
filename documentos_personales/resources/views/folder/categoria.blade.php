@extends('layouts.app')

@section('title', $tipo->nombre . ' - SGDP')

@section('sidebar')
<a href="{{ route('folder.index') }}" class="nav-btn">← Volver al Folder</a>

<div class="nav-section-label">Acciones</div>
<a href="{{ route('documentos.create', $tipo->id_tipo) }}" class="nav-btn">+ Subir Documento</a>

<div class="nav-section-label">Mi Cuenta</div>
<a href="{{ route('folder.papelera') }}" class="nav-btn">🗑 Papelera</a>
@endsection

@section('content')

<div class="flex-between" style="margin-bottom: 30px;">
    <div class="panel-header" style="margin-bottom: 0;">
        <h1>{{ $tipo->nombre }}</h1>
        <p>{{ $tipo->descripcion }} · {{ $documentos->count() }} documento(s)</p>
    </div>
    <a href="{{ route('documentos.create', $tipo->id_tipo) }}" class="btn btn-primary">
        + Subir PDF
    </a>
</div>

@if($documentos->isEmpty())
    <div class="empty-state">
        <div style="font-size: 2rem; color: var(--border-metal);">📄</div>
        <p>No tienes documentos en esta categoría.</p>
        <a href="{{ route('documentos.create', $tipo->id_tipo) }}"
           class="btn btn-gold" style="margin-top: 16px; display: inline-block;">
            Subir primer documento
        </a>
    </div>
@else
    <table class="data-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>N° Documento</th>
                <th>Emisor</th>
                <th>Emisión</th>
                @if($tipo->tiene_vencimiento)<th>Vence</th><th>Vigencia</th>@endif
                <th>Tamaño</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr>
                <td style="color: var(--text-bright);">{{ $doc->nombre_documento }}</td>
                <td>{{ $doc->numero_documento ?? '—' }}</td>
                <td>{{ $doc->entidad_emisora ?? '—' }}</td>
                <td>{{ $doc->fecha_emision ? $doc->fecha_emision->format('d/m/Y') : '—' }}</td>

                @if($tipo->tiene_vencimiento)
                <td>{{ $doc->fecha_vencimiento ? $doc->fecha_vencimiento->format('d/m/Y') : '—' }}</td>
                <td>
                    <span class="badge vigencia-{{ $doc->vigencia }}
                        {{ $doc->vigencia === 'vigente' ? 'badge-active' : ($doc->vigencia === 'por_vencer' ? 'badge-warn' : 'badge-danger') }}">
                        {{ strtoupper(str_replace('_', ' ', $doc->vigencia)) }}
                    </span>
                </td>
                @endif

                <td>{{ $doc->tamanio_legible }}</td>

                <td>
                    <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                        <a href="{{ route('documentos.ver', $doc->id_documento) }}"
                           class="btn btn-gold btn-sm" target="_blank">Ver</a>
                        <a href="{{ route('documentos.descargar', $doc->id_documento) }}"
                           class="btn btn-primary btn-sm">↓</a>
                        <button onclick="mostrarReemplazar('{{ $doc->id_documento }}')"
                                class="btn btn-sm"
                                style="background: transparent; border: 1px solid var(--border-metal); color: var(--text-dim); cursor: pointer;">
                            ↺
                        </button>
                        <form action="{{ route('documentos.papelera', $doc->id_documento) }}"
                              method="POST"
                              onsubmit="return confirm('¿Mover a la papelera?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">✕</button>
                        </form>
                    </div>

                    {{-- Form oculto de reemplazo --}}
                    <div id="reemplazar-{{ $doc->id_documento }}"
                         style="display: none; margin-top: 10px;">
                        <form action="{{ route('documentos.reemplazar', $doc->id_documento) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <input type="file" name="archivo_pdf" accept=".pdf"
                                       style="flex: 1; font-size: 0.75rem; padding: 6px;">
                                <button type="submit" class="btn btn-primary btn-sm">Subir</button>
                                <button type="button" class="btn btn-sm"
                                        style="background: transparent; border: 1px solid var(--border-metal); color: var(--text-dim); cursor: pointer;"
                                        onclick="mostrarReemplazar('{{ $doc->id_documento }}')">✕</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script>
function mostrarReemplazar(id) {
    const el = document.getElementById('reemplazar-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
</script>

@endsection
