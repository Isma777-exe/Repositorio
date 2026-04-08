@extends('layouts.app')

@section('title', 'SGDP - Sistema de Gestión de Documentos Personales')

@section('content')
<div style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px 20px;">

    {{-- Logo central --}}
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-family: 'Cinzel', serif; color: var(--imperial-gold); font-size: 3rem; letter-spacing: 6px; text-shadow: var(--glow);">
            SGDP
        </h1>
        <p style="color: var(--text-dim); font-size: 0.75rem; letter-spacing: 4px; margin-top: 8px;">
            SISTEMA DE GESTIÓN DE DOCUMENTOS PERSONALES
        </p>
        <div style="width: 80px; height: 1px; background: var(--imperial-gold); margin: 20px auto;"></div>
        <p style="color: var(--text-dim); font-size: 0.8rem; max-width: 500px;">
            Almacena y gestiona tus documentos personales de forma segura.
            Carnet de identidad, títulos, licencias y más.
        </p>
    </div>

    {{-- Cards de características --}}
    <div class="cards-grid" style="max-width: 800px; width: 100%; margin-bottom: 60px;">
        <div class="card">
            <h3 style="font-size: 1.4rem;">PDF</h3>
            <p>Solo formato PDF — seguro y universal</p>
        </div>
        <div class="card">
            <h3 style="font-size: 1.4rem;">📁</h3>
            <p>Folder personal por categoría</p>
        </div>
        <div class="card">
            <h3 style="font-size: 1.4rem;">🔒</h3>
            <p>Acceso protegido con roles</p>
        </div>
        <div class="card">
            <h3 style="font-size: 1.4rem;">⏰</h3>
            <p>Alertas de documentos por vencer</p>
        </div>
    </div>

    {{-- Botones de acceso --}}
    <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
        <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 14px 40px; font-size: 0.9rem;">
            Iniciar Sesión
        </a>
        <a href="{{ route('registro') }}" class="btn btn-gold" style="padding: 14px 40px; font-size: 0.9rem;">
            Registrarse
        </a>
    </div>

    {{-- Footer --}}
    <div style="margin-top: 80px; text-align: center; color: var(--text-dim); font-size: 0.7rem; letter-spacing: 2px;">
        UPDS · SUB-SEDE LA PAZ · INGENIERÍA DE SISTEMAS · TECNOLOGÍA WEB I · GRUPO 5 · 2026
    </div>
</div>
@endsection
