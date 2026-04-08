<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $usuario = User::where('email', $request->email)->first();

        // Verificar bloqueo por intentos fallidos
        if ($usuario && $usuario->bloqueado_hasta && $usuario->bloqueado_hasta->isFuture()) {
            $minutos = now()->diffInMinutes($usuario->bloqueado_hasta);
            return back()->withErrors([
                'email' => "Cuenta bloqueada. Intenta en {$minutos} minuto(s).",
            ]);
        }

        // Verificar credenciales
        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            if ($usuario) {
                DB::statement('SELECT registrar_intento_fallido(?)', [$request->email]);
            }
            return back()->withErrors(['email' => 'Credenciales incorrectas.']);
        }

        if ($usuario->estado !== 'activo') {
            return back()->withErrors(['email' => 'Tu cuenta está inactiva o bloqueada.']);
        }

        // Login exitoso
        Auth::login($usuario, $request->boolean('remember'));
        DB::statement('SELECT resetear_intentos_login(?)', [$usuario->id_usuario]);
        $request->session()->regenerate();

        return $usuario->esAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('folder.index');
    }

    public function showRegistro()
    {
        return view('auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'nombre'                => 'required|string|max:100',
            'apellido'              => 'required|string|max:100',
            'email'                 => 'required|email|unique:usuarios,email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $usuario = User::create([
            'id_usuario'    => Str::uuid(),
            'nombre'        => $request->nombre,
            'apellido'      => $request->apellido,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'rol'           => 'usuario',
            'estado'        => 'activo',
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('folder.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}