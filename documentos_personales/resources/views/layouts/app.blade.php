<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SGDP - Sistema de Gestión de Documentos Personales')</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-deep:      #050505;
            --bg-panel:     #1a1a1d;
            --border-metal: #4e4e50;
            --imperial-gold:#c5a059;
            --guard-green:  #3b4d3e;
            --alert-red:    #8b0000;
            --text-dim:     #888888;
            --text-bright:  #c5c6c7;
            --glow:         0 0 10px rgba(197,160,89,0.3);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-deep);
            background-image: radial-gradient(circle at center, #111 0%, #000 100%);
            color: var(--text-bright);
            font-family: 'Share Tech Mono', monospace;
            min-height: 100vh;
        }

        /* ── Sidebar ─────────────────────────────────────── */
        .sidebar {
            width: 250px;
            background: var(--bg-panel);
            border-right: 2px solid var(--imperial-gold);
            position: fixed;
            height: 100vh;
            padding: 20px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-metal);
            padding-bottom: 20px;
        }

        .logo-area h2 {
            font-family: 'Cinzel', serif;
            color: var(--imperial-gold);
            font-size: 1.5rem;
            text-shadow: var(--glow);
            letter-spacing: 3px;
        }

        .logo-area span { font-size: 0.65rem; color: var(--text-dim); display: block; margin-top: 4px; }

        .nav-section-label {
            font-size: 0.6rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 12px 0 6px;
            border-top: 1px solid var(--border-metal);
            margin-top: 8px;
        }

        .nav-btn {
            background: transparent;
            border: 1px solid var(--border-metal);
            color: var(--text-dim);
            padding: 12px 15px;
            margin-bottom: 6px;
            cursor: pointer;
            text-align: left;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.8rem;
            text-transform: uppercase;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
            letter-spacing: 1px;
        }

        .nav-btn:hover, .nav-btn.active {
            border-color: var(--imperial-gold);
            color: var(--imperial-gold);
            background: rgba(197,160,89,0.05);
            padding-left: 20px;
        }

        .nav-btn.danger { border-color: var(--alert-red); color: var(--alert-red); }
        .nav-btn.danger:hover { background: var(--alert-red); color: #fff; padding-left: 15px; }

        /* ── Main ────────────────────────────────────────── */
        .main-content { margin-left: 250px; padding: 40px; min-height: 100vh; }

        .panel-header {
            border-left: 4px solid var(--imperial-gold);
            padding-left: 15px;
            margin-bottom: 30px;
        }

        .panel-header h1, .panel-header h2 {
            font-family: 'Cinzel', serif;
            color: var(--text-bright);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .panel-header h2 { font-size: 1.1rem; }
        .panel-header p  { color: var(--text-dim); font-size: 0.85rem; margin-top: 4px; }

        /* ── Cards ───────────────────────────────────────── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border-metal);
            padding: 20px;
            text-align: center;
            transition: border-color 0.2s;
        }

        .card:hover { border-color: var(--imperial-gold); }

        .card h3 { color: var(--imperial-gold); font-size: 2rem; margin-bottom: 8px; }
        .card p   { color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }

        .card.warn h3  { color: #c5a059; }
        .card.danger h3{ color: var(--alert-red); }
        .card.ok h3    { color: #3b8b3e; }

        /* ── Forms ───────────────────────────────────────── */
        .form-panel {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--border-metal);
            padding: 25px;
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: span 2; }

        label {
            color: var(--imperial-gold);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        input, select, textarea {
            width: 100%;
            background: #000;
            border: 1px solid var(--guard-green);
            color: var(--text-bright);
            padding: 10px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--imperial-gold);
            box-shadow: 0 0 5px rgba(197,160,89,0.15);
        }

        input[type="file"] { padding: 8px; cursor: pointer; }

        /* ── Botones ─────────────────────────────────────── */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--guard-green);
            color: #fff;
        }
        .btn-primary:hover { background: var(--imperial-gold); color: #000; }

        .btn-gold {
            background: transparent;
            border: 1px solid var(--imperial-gold);
            color: var(--imperial-gold);
        }
        .btn-gold:hover { background: var(--imperial-gold); color: #000; }

        .btn-danger {
            background: transparent;
            border: 1px solid var(--alert-red);
            color: var(--alert-red);
        }
        .btn-danger:hover { background: var(--alert-red); color: #fff; }

        .btn-sm { padding: 5px 10px; font-size: 0.7rem; }

        .btn-full { width: 100%; text-align: center; padding: 14px; }

        /* ── Tablas ──────────────────────────────────────── */
        .data-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }

        .data-table th {
            text-align: left;
            padding: 10px 12px;
            border-bottom: 2px solid var(--imperial-gold);
            color: var(--imperial-gold);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-metal);
            color: var(--text-dim);
        }

        .data-table tr:hover td {
            background: rgba(197,160,89,0.04);
            color: var(--text-bright);
        }

        /* ── Badges ──────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 0.7rem;
            border: 1px solid;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-active  { border-color: #3b8b3e; color: #3b8b3e; }
        .badge-warn    { border-color: var(--imperial-gold); color: var(--imperial-gold); }
        .badge-danger  { border-color: var(--alert-red); color: var(--alert-red); }
        .badge-dim     { border-color: var(--border-metal); color: var(--text-dim); }

        /* ── Alertas ─────────────────────────────────────── */
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border: 1px solid;
            font-size: 0.85rem;
        }

        .alert-success { background: rgba(59,77,62,0.2); border-color: var(--guard-green); color: #6bbd6e; }
        .alert-error   { background: rgba(139,0,0,0.2);  border-color: var(--alert-red);   color: #ff6b6b; }
        .alert-warn    { background: rgba(197,160,89,0.1);border-color: var(--imperial-gold);color: var(--imperial-gold); }

        /* ── Vigencia ─────────────────────────────────────── */
        .vigencia-vigente   { color: #3b8b3e; }
        .vigencia-por_vencer{ color: var(--imperial-gold); }
        .vigencia-vencido   { color: var(--alert-red); }

        /* ── Scrollbar ───────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #000; }
        ::-webkit-scrollbar-thumb { background: var(--border-metal); }
        ::-webkit-scrollbar-thumb:hover { background: var(--imperial-gold); }

        /* ── Utilidades ──────────────────────────────────── */
        .mt-lg { margin-top: 40px; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .gap-sm { gap: 8px; }
        .text-center { text-align: center; }
        .text-gold { color: var(--imperial-gold); }
        .text-dim  { color: var(--text-dim); }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-dim); }
        .empty-state p { margin-top: 10px; font-size: 0.85rem; }
    </style>
</head>
<body>

@auth
<nav class="sidebar">
    <div class="logo-area">
        <h2>SGDP</h2>
        <span>GRUPO 5 · UPDS · 2026</span>
    </div>

    @yield('sidebar')

    <div style="margin-top: auto; padding-top: 16px; border-top: 1px solid var(--border-metal);">
        <p style="font-size: 0.75rem; color: var(--text-dim); margin-bottom: 10px; word-break: break-all;">
            {{ auth()->user()->nombre }} {{ auth()->user()->apellido }}<br>
            <span style="font-size: 0.65rem; color: var(--guard-green);">
                {{ strtoupper(auth()->user()->rol) }}
            </span>
        </p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-btn danger">⏻ Cerrar Sesión</button>
        </form>
    </div>
</nav>

<main class="main-content">
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">✕ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <div>✕ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</main>

@else
    @yield('content')
@endauth

</body>
</html>
