{{-- ============================================================
     resources/views/admin/usuarios/index.blade.php
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Gestión de Usuarios - SGDP')

@section('sidebar')
<a href="{{ route('admin.dashboard') }}" class="nav-btn">← Panel General</a>

<div class="nav-section-label">Gestión</div>
<a href="{{ route('admin.usuarios.index') }}" class="nav-btn active">👥 Usuarios</a>
<a href="{{ route('admin.tipos.index') }}" class="nav-btn">📁 Tipos de Documento</a>
@endsection

@section('content')

<div class="flex-between" style="margin-bottom: 30px;">
    <div class="panel-header" style="margin-bottom: 0;">
        <h1>Gestión de Usuarios</h1>
        <p>{{ $usuarios->total() }} usuario(s) registrado(s)</p>
    </div>
    <button onclick="document.getElementById('modal-crear').style.display='flex'"
            class="btn btn-primary">+ Nuevo Usuario</button>
</div>

{{-- Buscador --}}
<div class="form-panel" style="padding: 16px 20px; margin-bottom: 20px;">
    <form method="GET" action="{{ route('admin.usuarios.index') }}">
        <div style="display: flex; gap: 12px; align-items: flex-end;">
            <div class="form-group" style="flex: 1; margin: 0;">
                <label>Buscar</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Nombre o email...">
            </div>
            <button type="submit" class="btn btn-gold" style="padding: 10px 20px;">Buscar</button>
            @if(request('buscar'))
                <a href="{{ route('admin.usuarios.index') }}" class="btn" style="padding: 10px 16px; border: 1px solid var(--border-metal); color: var(--text-dim);">✕</a>
            @endif
        </div>
    </form>
</div>

{{-- Tabla --}}
<table class="data-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Docs</th>
            <th>Estado</th>
            <th>Registro</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($usuarios as $usuario)
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
            <td style="color: var(--imperial-gold);">{{ $usuario->total_documentos }}</td>
            <td>
                <span class="badge
                    {{ $usuario->estado === 'activo' ? 'badge-active' : ($usuario->estado === 'bloqueado' ? 'badge-danger' : 'badge-dim') }}">
                    {{ strtoupper($usuario->estado) }}
                </span>
            </td>
            <td>{{ $usuario->fecha_registro->format('d/m/Y') }}</td>
            <td>
                <div style="display: flex; gap: 6px;">
                    <a href="{{ route('admin.usuarios.ver', $usuario->id_usuario) }}"
                       class="btn btn-gold btn-sm">Ver</a>

                    <form action="{{ route('admin.usuarios.estado', $usuario->id_usuario) }}"
                          method="POST">
                        @csrf
                        @if($usuario->estado === 'activo')
                            <input type="hidden" name="estado" value="bloqueado">
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('¿Bloquear este usuario?')">
                                Bloquear
                            </button>
                        @else
                            <input type="hidden" name="estado" value="activo">
                            <button type="submit" class="btn btn-sm"
                                    style="background: transparent; border: 1px solid #3b8b3e; color: #3b8b3e; cursor: pointer;">
                                Activar
                            </button>
                        @endif
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="empty-state">No se encontraron usuarios.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Paginación --}}
<div style="margin-top: 20px; color: var(--text-dim); font-size: 0.8rem;">
    {{ $usuarios->withQueryString()->links() }}
</div>

{{-- Modal crear usuario --}}
<div id="modal-crear"
     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85);
            z-index: 100; align-items: center; justify-content: center;">
    <div style="background: var(--bg-panel); border: 1px solid var(--imperial-gold);
                padding: 30px; width: 100%; max-width: 500px;">

        <div class="flex-between" style="margin-bottom: 20px;">
            <h2 style="font-family: 'Cinzel', serif; color: var(--imperial-gold); font-size: 1rem; letter-spacing: 2px;">
                NUEVO USUARIO
            </h2>
            <button onclick="document.getElementById('modal-crear').style.display='none'"
                    style="background: none; border: none; color: var(--text-dim); font-size: 1.2rem; cursor: pointer;">✕</button>
        </div>

        <form action="{{ route('admin.usuarios.crear') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Apellido *</label>
                    <input type="text" name="apellido" required>
                </div>
                <div class="form-group full">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Contraseña *</label>
                    <input type="password" name="password" minlength="8" required>
                </div>
                <div class="form-group">
                    <label>Rol *</label>
                    <select name="rol" required>
                        <option value="usuario">Usuario</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>
                <div style="grid-column: span 2; display: flex; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px;">Crear</button>
                    <button type="button" class="btn btn-gold" style="flex: 1; padding: 12px;"
                            onclick="document.getElementById('modal-crear').style.display='none'">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
