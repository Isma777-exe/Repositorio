@extends('layouts.app')

@section('title', $usuario->nombre . ' - SGDP')

@section('sidebar')
<a href="{{ route('admin.usuarios.index') }}" class="nav-btn">← Gestión Usuarios</a>
<a href="{{ route('admin.dashboard') }}" class="nav-btn">📊 Panel General</a>
<a href="{{ route('admin.tipos.index') }}" class="nav-btn">📁 Tipos de Documento</a>
@endsection

@section('content')

<div class="panel-header">
    <h1>{{ $usuario->nombre }} {{ $usuario->apellido }}</h1>
    <p>{{ $usuario->email }} ·
        <span class="badge {{ $usuario->estado === 'activo' ? 'badge-active' : 'badge-danger' }}">
            {{ strtoupper($usuario->estado) }}
        </span>
    </p>
</div>

{{-- Info del usuario --}}
<div class="cards-grid" style="margin-bottom: 30px;">
    <div class="card">
        <h3>{{ $documentos->count() }}</h3>
        <p>Documentos activos</p>
    </div>
    <div class="card">
        <h3>{{ $documentos->where('vigencia', 'vencido')->count() }}</h3>
        <p>Vencidos</p>
    </div>
    <div class="card">
        <h3>{{ $usuario->fecha_registro->format('d/m/Y') }}</h3>
        <p>Fecha de registro</p>
    </div>
    <div class="card">
        <h3>{{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->format('d/m/Y') : '—' }}</h3>
        <p>Último acceso</p>
    </div>
</div>

{{-- Cambiar estado --}}
<div class="form-panel" style="display: flex; gap: 12px; align-items: center; padding: 16px 20px; margin-bottom: 30px;">
    <span style="color: var(--text-dim); font-size: 0.8rem; flex: 1;">Estado de la cuenta:</span>
    <form action="{{ route('admin.usuarios.estado', $usuario->id_usuario) }}" method="POST"
          style="display: flex; gap: 8px;">
        @csrf
        <select name="estado" style="padding: 8px; width: auto;">
            <option value="activo"    {{ $usuario->estado === 'activo'    ? 'selected' : '' }}>Activo</option>
            <option value="inactivo"  {{ $usuario->estado === 'inactivo'  ? 'selected' : '' }}>Inactivo</option>
            <option value="bloqueado" {{ $usuario->estado === 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
        </select>
        <button type="submit" class="btn btn-gold btn-sm">Guardar</button>
    </form>
</div>

{{-- Documentos del usuario --}}
<div class="panel-header" style="margin-bottom: 16px;">
    <h2>Documentos ({{ $documentos->count() }})</h2>
</div>

@if($documentos->isEmpty())
    <div class="empty-state">
        <p>Este usuario no tiene documentos subidos.</p>
    </div>
@else
    <table class="data-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>N° Documento</th>
                <th>Vigencia</th>
                <th>Tamaño</th>
                <th>Subido</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr>
                <td style="color: var(--text-bright);">{{ $doc->nombre_documento }}</td>
                <td>{{ $doc->tipo->nombre }}</td>
                <td>{{ $doc->numero_documento ?? '—' }}</td>
                <td>
                    <span class="badge
                        {{ $doc->vigencia === 'vigente' ? 'badge-active' : ($doc->vigencia === 'por_vencer' ? 'badge-warn' : 'badge-danger') }}">
                        {{ strtoupper(str_replace('_', ' ', $doc->vigencia)) }}
                    </span>
                </td>
                <td>{{ $doc->tamanio_legible }}</td>
                <td>{{ $doc->fecha_creacion->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection
