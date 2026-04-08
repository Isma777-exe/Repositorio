@extends('layouts.app')

@section('title', 'Mi Folder - SGDP')

@section('sidebar')
<a href="{{ route('folder.index') }}" class="nav-btn active">📁 Mi Folder</a>

<div class="nav-section-label">Categorías</div>
@foreach($tipos as $tipo)
    <a href="{{ route('folder.categoria', $tipo->id_tipo) }}" class="nav-btn">
        📄 {{ $tipo->nombre }}
        @if($tipo->total > 0)
            <span style="float: right; color: var(--imperial-gold); font-size: 0.75rem;">{{ $tipo->total }}</span>
        @endif
    </a>
@endforeach

<div class="nav-section-label">Mi Cuenta</div>
<a href="{{ route('folder.papelera') }}" class="nav-btn">🗑 Papelera</a>
@endsection

@section('content')

<div class="flex-between" style="margin-bottom: 30px;">
    <div class="panel-header" style="margin-bottom: 0;">
        <h1>Mi Folder</h1>
        <p>Bienvenido, {{ auth()->user()->nombre }}. Gestiona tus documentos personales.</p>
    </div>
</div>

{{-- Resumen --}}
<div class="cards-grid">
    <div class="card">
        <h3>{{ $totalDocumentos }}</h3>
        <p>Documentos totales</p>
    </div>
    <div class="card {{ $porVencer->count() > 0 ? 'warn' : '' }}">
        <h3>{{ $porVencer->count() }}</h3>
        <p>Por vencer (30 días)</p>
    </div>
    <div class="card {{ $vencidos > 0 ? 'danger' : '' }}">
        <h3>{{ $vencidos }}</h3>
        <p>Documentos vencidos</p>
    </div>
    <div class="card">
        <h3>{{ $tipos->count() }}</h3>
        <p>Categorías disponibles</p>
    </div>
</div>

{{-- Alertas de vencimiento --}}
@if($porVencer->count() > 0)
<div class="alert alert-warn" style="margin-bottom: 30px;">
    ⚠ Tienes {{ $porVencer->count() }} documento(s) por vencer en los próximos 30 días.
</div>
@endif

{{-- Categorías con acceso rápido --}}
<div class="panel-header mt-lg">
    <h2>Categorías</h2>
</div>

<div class="cards-grid">
    @foreach($tipos as $tipo)
    <a href="{{ route('folder.categoria', $tipo->id_tipo) }}"
       style="text-decoration: none; display: block;">
        <div class="card" style="cursor: pointer; text-align: left; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <span style="color: var(--imperial-gold); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                    {{ $tipo->nombre }}
                </span>
                <span style="background: rgba(197,160,89,0.1); border: 1px solid var(--imperial-gold);
                             color: var(--imperial-gold); padding: 2px 8px; font-size: 0.7rem;">
                    {{ $tipo->total }}
                </span>
            </div>
            <p style="font-size: 0.75rem; margin-top: 4px;">{{ $tipo->descripcion }}</p>
            @if($tipo->tiene_vencimiento)
                <p style="font-size: 0.65rem; color: var(--guard-green); margin-top: 8px; text-transform: uppercase;">
                    ● Con vencimiento
                </p>
            @endif
        </div>
    </a>
    @endforeach
</div>

{{-- Documentos por vencer --}}
@if($porVencer->count() > 0)
<div class="panel-header mt-lg">
    <h2>Documentos por Vencer</h2>
</div>

<table class="data-table">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Tipo</th>
            <th>Vence</th>
            <th>Días restantes</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($porVencer as $doc)
        <tr>
            <td>{{ $doc->nombre_documento }}</td>
            <td>{{ $doc->tipo->nombre }}</td>
            <td>{{ $doc->fecha_vencimiento->format('d/m/Y') }}</td>
            <td>
                <span class="badge badge-warn">
                    {{ now()->diffInDays($doc->fecha_vencimiento) }} días
                </span>
            </td>
            <td>
                <a href="{{ route('documentos.ver', $doc->id_documento) }}"
                   class="btn btn-gold btn-sm">Ver PDF</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

@endsection
