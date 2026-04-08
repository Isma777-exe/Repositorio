@extends('layouts.app')

@section('title', 'Tipos de Documento - SGDP')

@section('sidebar')
<a href="{{ route('admin.dashboard') }}" class="nav-btn">← Panel General</a>

<div class="nav-section-label">Gestión</div>
<a href="{{ route('admin.usuarios.index') }}" class="nav-btn">👥 Usuarios</a>
<a href="{{ route('admin.tipos.index') }}" class="nav-btn active">📁 Tipos de Documento</a>
@endsection

@section('content')

<div class="flex-between" style="margin-bottom: 30px;">
    <div class="panel-header" style="margin-bottom: 0;">
        <h1>Tipos de Documento</h1>
        <p>Categorías disponibles para el folder de cada usuario</p>
    </div>
    <button onclick="document.getElementById('modal-tipo').style.display='flex'"
            class="btn btn-primary">+ Nuevo Tipo</button>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Vence</th>
            <th>N° Requerido</th>
            <th>Emisor Requerido</th>
            <th>Documentos</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tipos as $tipo)
        <tr>
            <td style="color: var(--text-bright);">{{ $tipo->nombre }}</td>
            <td>{{ $tipo->descripcion ?? '—' }}</td>
            <td>
                <span class="badge {{ $tipo->tiene_vencimiento ? 'badge-warn' : 'badge-dim' }}">
                    {{ $tipo->tiene_vencimiento ? 'SÍ' : 'NO' }}
                </span>
            </td>
            <td>
                <span class="badge {{ $tipo->numero_requerido ? 'badge-active' : 'badge-dim' }}">
                    {{ $tipo->numero_requerido ? 'SÍ' : 'NO' }}
                </span>
            </td>
            <td>
                <span class="badge {{ $tipo->emisor_requerido ? 'badge-active' : 'badge-dim' }}">
                    {{ $tipo->emisor_requerido ? 'SÍ' : 'NO' }}
                </span>
            </td>
            <td style="color: var(--imperial-gold);">{{ $tipo->documentos_count }}</td>
            <td>
                <span class="badge {{ $tipo->activo ? 'badge-active' : 'badge-dim' }}">
                    {{ $tipo->activo ? 'ACTIVO' : 'INACTIVO' }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="empty-state">No hay tipos de documento registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Modal nuevo tipo --}}
<div id="modal-tipo"
     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85);
            z-index: 100; align-items: center; justify-content: center;">
    <div style="background: var(--bg-panel); border: 1px solid var(--imperial-gold);
                padding: 30px; width: 100%; max-width: 520px;">

        <div class="flex-between" style="margin-bottom: 20px;">
            <h2 style="font-family: 'Cinzel', serif; color: var(--imperial-gold); font-size: 1rem; letter-spacing: 2px;">
                NUEVO TIPO DE DOCUMENTO
            </h2>
            <button onclick="document.getElementById('modal-tipo').style.display='none'"
                    style="background: none; border: none; color: var(--text-dim); font-size: 1.2rem; cursor: pointer;">✕</button>
        </div>

        <form action="{{ route('admin.tipos.guardar') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" placeholder="Ej: Certificado de residencia" required>
                </div>
                <div class="form-group full">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" placeholder="Descripción breve del tipo">
                </div>

                {{-- Checkboxes --}}
                <div class="form-group full" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; text-transform: none; font-size: 0.8rem;">
                        <input type="checkbox" name="tiene_vencimiento" value="1"
                               style="width: auto; accent-color: var(--imperial-gold);">
                        Tiene vencimiento
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; text-transform: none; font-size: 0.8rem;">
                        <input type="checkbox" name="numero_requerido" value="1"
                               style="width: auto; accent-color: var(--imperial-gold);">
                        N° requerido
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; text-transform: none; font-size: 0.8rem;">
                        <input type="checkbox" name="emisor_requerido" value="1"
                               style="width: auto; accent-color: var(--imperial-gold);">
                        Emisor requerido
                    </label>
                </div>

                <div style="grid-column: span 2; display: flex; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px;">Crear</button>
                    <button type="button" class="btn btn-gold" style="flex: 1; padding: 12px;"
                            onclick="document.getElementById('modal-tipo').style.display='none'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
