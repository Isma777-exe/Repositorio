@extends('layouts.app')

@section('title', 'Iniciar Sesión - SGDP')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div style="width: 100%; max-width: 420px;">

        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-family: 'Cinzel', serif; color: var(--imperial-gold); font-size: 2rem; letter-spacing: 4px;">SGDP</h1>
            <p style="color: var(--text-dim); font-size: 0.75rem; letter-spacing: 3px; margin-top: 6px;">ACCESO AL SISTEMA</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>✕ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="form-panel">
            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="usuario@correo.com"
                           autofocus required>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                </div>

                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                    <input type="checkbox" name="remember" id="remember"
                           style="width: auto; accent-color: var(--imperial-gold);">
                    <label for="remember" style="color: var(--text-dim); font-size: 0.75rem; cursor: pointer; text-transform: none; letter-spacing: 0;">
                        Mantener sesión iniciada
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    Ingresar al Sistema
                </button>
            </form>
        </div>

        <p style="text-align: center; color: var(--text-dim); font-size: 0.8rem; margin-top: 20px;">
            ¿No tienes cuenta?
            <a href="{{ route('registro') }}" style="color: var(--imperial-gold); text-decoration: none;">Regístrate aquí</a>
        </p>

    </div>
</div>
@endsection
