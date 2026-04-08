@extends('layouts.app')

@section('title', 'Panel de Administrador - SGDP')

@section('sidebar')
<a href="{{ route('admin.dashboard') }}" class="nav-btn active">📊 Panel General</a>

<div class="nav-section-label">Gestión</div>
<a href="{{ route('admin.usuarios.index') }}" class="nav-btn">👥 Usuarios</a>
<a href="{{ route('admin.tipos.index') }}" class="nav-btn">📁 Tipos de Documento</a>
@endsection

@section('content')

<div class="panel-header">
    <h1>Panel de Administración</h1>
    <p>Control general del sistema · {{ now()->format('d/m/Y H:i') }}</p>
</div>

{{-- Estadísticas --}}
<div class="cards-grid">
    <div class="card">
        <h3>{{ $stats['total_usuarios'] }}</h3>
        <p>Usuarios Registrados</p>
    </div>
    <div class="card">
        <h3>{{ $stats['total_documentos'] }}</h3>
        <p>Documentos Activos</p>
    </div>
    <div class="card {{ $stats['por_vencer'] > 0 ? 'warn' : '' }}">
        <h3>{{ $stats['por_vencer'] }}</h3>
        <p>Por Vencer (30 días)</p>
    </div>
    <div class="card {{ $stats['vencidos'] > 0 ? 'danger' : '' }}">
        <h3>{{ $stats['vencidos'] }}</h3>
        <p>Documentos Vencidos</p>
    </div>
</div>

{{-- Últimos usuarios registrados --}}
<div class="panel-header mt-lg flex-between" style="margin-bottom: 16px;">
    <div>
        <h2>Últimos Usuarios Registrados</h2>
    </div>
    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-gold btn-sm">Ver todos</a>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($ultimosUsuarios as $usuario)
        <tr>
            <td style="color: var(--text-bright);">
                {{ $usuario->nombre }} {{ $usuario->apellido }}
            </td>
            <td>{{ $usuario->email }}</td>
            <td>
                <span class="badge {{ $usuario->rol === 'administrador' ? 'badge-warn' : 'badge-dim' }}">
                    {{ strtoupper($usuario->rol) }}
                </span>
            </td>
            <td>
                <span class="badge {{ $usuario->estado === 'activo' ? 'badge-active' : 'badge-danger' }}">
                    {{ strtoupper($usuario->estado) }}
                </span>
            </td>
            <td>{{ $usuario->fecha_registro->format('d/m/Y') }}</td>
            <td>
                <a href="{{ route('admin.usuarios.ver', $usuario->id_usuario) }}"
                   class="btn btn-gold btn-sm">Ver</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="empty-state">No hay usuarios registrados aún.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
