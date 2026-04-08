@extends('layouts.app')

@section('title', 'Subir Documento - SGDP')

@section('sidebar')
<a href="{{ route('folder.categoria', $tipo->id_tipo) }}" class="nav-btn">← Volver a {{ $tipo->nombre }}</a>
<a href="{{ route('folder.index') }}" class="nav-btn">📁 Mi Folder</a>
@endsection

@section('content')

<div class="panel-header">
    <h1>Subir Documento</h1>
    <p>Categoría: {{ $tipo->nombre }}</p>
</div>

<div class="form-panel" style="max-width: 680px;">
    <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_tipo" value="{{ $tipo->id_tipo }}">

        <div class="form-grid">

            {{-- Nombre descriptivo --}}
            <div class="form-group full">
                <label for="nombre_documento">Nombre del Documento *</label>
                <input type="text" id="nombre_documento" name="nombre_documento"
                       value="{{ old('nombre_documento') }}"
                       placeholder="Ej: Carnet de identidad — frente y reverso"
                       required>
            </div>

            {{-- Número oficial --}}
            @if($tipo->numero_requerido)
            <div class="form-group">
                <label for="numero_documento">Número de Documento *</label>
                <input type="text" id="numero_documento" name="numero_documento"
                       value="{{ old('numero_documento') }}"
                       placeholder="Ej: 1234567 LP" required>
            </div>
            @else
            <div class="form-group">
                <label for="numero_documento">Número de Documento</label>
                <input type="text" id="numero_documento" name="numero_documento"
                       value="{{ old('numero_documento') }}"
                       placeholder="Opcional">
            </div>
            @endif

            {{-- Emisor --}}
            @if($tipo->emisor_requerido)
            <div class="form-group">
                <label for="entidad_emisora">Entidad Emisora *</label>
                <input type="text" id="entidad_emisora" name="entidad_emisora"
                       value="{{ old('entidad_emisora') }}"
                       placeholder="Ej: SEGIP, UMSA, DIPROVE" required>
            </div>
            @else
            <div class="form-group">
                <label for="entidad_emisora">Entidad Emisora</label>
                <input type="text" id="entidad_emisora" name="entidad_emisora"
                       value="{{ old('entidad_emisora') }}"
                       placeholder="Opcional">
            </div>
            @endif

            {{-- País y departamento --}}
            <div class="form-group">
                <label for="pais_emision">País de Emisión</label>
                <select id="pais_emision" name="pais_emision">
                    <option value="">— Seleccionar —</option>
                    <option value="BO" {{ old('pais_emision','BO') == 'BO' ? 'selected' : '' }}>Bolivia</option>
                    <option value="AR" {{ old('pais_emision') == 'AR' ? 'selected' : '' }}>Argentina</option>
                    <option value="PE" {{ old('pais_emision') == 'PE' ? 'selected' : '' }}>Perú</option>
                    <option value="CL" {{ old('pais_emision') == 'CL' ? 'selected' : '' }}>Chile</option>
                    <option value="BR" {{ old('pais_emision') == 'BR' ? 'selected' : '' }}>Brasil</option>
                </select>
            </div>

            <div class="form-group">
                <label for="departamento_emision">Departamento</label>
                <input type="text" id="departamento_emision" name="departamento_emision"
                       value="{{ old('departamento_emision') }}"
                       placeholder="Ej: La Paz, Cochabamba">
            </div>

            {{-- Fechas --}}
            <div class="form-group">
                <label for="fecha_emision">Fecha de Emisión</label>
                <input type="date" id="fecha_emision" name="fecha_emision"
                       value="{{ old('fecha_emision') }}">
            </div>

            @if($tipo->tiene_vencimiento)
            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de Vencimiento *</label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento"
                       value="{{ old('fecha_vencimiento') }}" required>
            </div>
            @else
            <div class="form-group">
                <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                <input type="date" id="fecha_vencimiento" name="fecha_vencimiento"
                       value="{{ old('fecha_vencimiento') }}">
            </div>
            @endif

            {{-- Archivo PDF --}}
            <div class="form-group full">
                <label for="archivo_pdf">Archivo PDF * (máx. 20 MB)</label>
                <input type="file" id="archivo_pdf" name="archivo_pdf"
                       accept=".pdf" required>
                <span style="font-size: 0.7rem; color: var(--text-dim); margin-top: 4px;">
                    Solo se aceptan archivos en formato PDF.
                </span>
            </div>

            {{-- Notas --}}
            <div class="form-group full">
                <label for="notas">Notas Personales</label>
                <textarea id="notas" name="notas" rows="3"
                          placeholder="Observaciones opcionales sobre este documento...">{{ old('notas') }}</textarea>
            </div>

            {{-- Botones --}}
            <div style="grid-column: span 2; display: flex; gap: 12px; margin-top: 8px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 14px;">
                    Subir Documento
                </button>
                <a href="{{ route('folder.categoria', $tipo->id_tipo) }}"
                   class="btn btn-gold" style="flex: 1; padding: 14px; text-align: center;">
                    Cancelar
                </a>
            </div>

        </div>
    </form>
</div>

@endsection
