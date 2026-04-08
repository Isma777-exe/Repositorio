<?php


namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Dashboard admin: totales del sistema
    public function dashboard()
    {
        $stats = [
            'total_usuarios'   => User::where('rol', 'usuario')->count(),
            'total_documentos' => Documento::activos()->count(),
            'por_vencer'       => Documento::activos()->porVencer(30)->count(),
            'vencidos'         => Documento::activos()->vencidos()->count(),
        ];

        $ultimosUsuarios = User::where('rol', 'usuario')
            ->orderBy('fecha_registro', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'ultimosUsuarios'));
    }

    // Listado de usuarios
    public function usuarios(Request $request)
    {
        $usuarios = User::where('rol', 'usuario')
            ->when($request->buscar, fn($q) =>
                $q->where(DB::raw("nombre || ' ' || apellido"), 'ilike', "%{$request->buscar}%")
                  ->orWhere('email', 'ilike', "%{$request->buscar}%")
            )
            ->withCount([
                'documentos as total_documentos'
            ])
            ->orderBy('fecha_registro', 'desc')
            ->paginate(20);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    // Ver documentos de un usuario específico
    public function verUsuario(string $idUsuario)
    {
        $usuario = User::where('rol', 'usuario')->findOrFail($idUsuario);

        $documentos = Documento::where('id_usuario', $idUsuario)
            ->activos()
            ->with('tipo')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $tipos = TipoDocumento::activos()->get();

        return view('admin.usuarios.ver', compact('usuario', 'documentos', 'tipos'));
    }

    // Cambiar estado de un usuario (activo / inactivo / bloqueado)
    public function cambiarEstado(Request $request, string $idUsuario)
    {
        $request->validate([
            'estado' => 'required|in:activo,inactivo,bloqueado',
        ]);

        $usuario = User::where('rol', 'usuario')->findOrFail($idUsuario);
        $usuario->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado actualizado.');
    }

    // Crear usuario desde el panel admin
    public function crearUsuario(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email'    => 'required|email|unique:usuarios,email',
            'password' => 'required|min:8',
            'rol'      => 'required|in:administrador,usuario',
        ]);

        User::create([
            'id_usuario'    => Str::uuid(),
            'nombre'        => $request->nombre,
            'apellido'      => $request->apellido,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'rol'           => $request->rol,
            'estado'        => 'activo',
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    // Gestión de tipos de documento
    public function tiposDocumento()
    {
        $tipos = TipoDocumento::withCount('documentos')->orderBy('nombre')->get();
        return view('admin.tipos.index', compact('tipos'));
    }

    public function guardarTipo(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:100|unique:tipos_documento,nombre',
            'descripcion'       => 'nullable|string|max:255',
            'tiene_vencimiento' => 'boolean',
            'numero_requerido'  => 'boolean',
            'emisor_requerido'  => 'boolean',
        ]);

        TipoDocumento::create($request->only([
            'nombre', 'descripcion',
            'tiene_vencimiento', 'numero_requerido', 'emisor_requerido',
        ]));

        return back()->with('success', 'Tipo de documento creado.');
    }
}
