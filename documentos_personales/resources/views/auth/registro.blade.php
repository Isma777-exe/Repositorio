@extends('layouts.app')

@section('title', 'Registro - SGDP')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div style="width: 100%; max-width: 500px;">

        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-family: 'Cinzel', serif; color: var(--imperial-gold); font-size: 2rem; letter-spacing: 4px;">SGDP</h1>
            <p style="color: var(--text-dim); font-size: 0.75rem; letter-spacing: 3px; margin-top: 6px;">NUEVO REGISTRO</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>✕ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="form-panel">
            <form action="{{ route('registro') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre"
                               value="{{ old('nombre') }}" placeholder="Juan" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido"
                               value="{{ old('apellido') }}" placeholder="Pérez" required>
                    </div>

                    <div class="form-group full">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}" placeholder="usuario@correo.com" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password"
                               placeholder="Mínimo 8 caracteres" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Repite la contraseña" required>
                    </div>

                    <div class="form-group full" style="margin-top: 8px;">
                        <button type="submit" class="btn btn-primary btn-full">
                            Crear Cuenta
                        </button>
                    </div>
                </div>

            </form>
        </div>

        <p style="text-align: center; color: var(--text-dim); font-size: 0.8rem; margin-top: 20px;">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" style="color: var(--imperial-gold); text-decoration: none;">Inicia sesión</a>
        </p>

    </div>
</div>
@endsection
