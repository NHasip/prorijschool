<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pro Rijschool Portal' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700&family=Material+Symbols+Outlined:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f6f5;
            --surface: #ffffff;
            --border: #c4cec7;
            --text: #1f2b38;
            --muted: #5f6674;
            --primary: #006d37;
            --primary-soft: #eaf4ef;
            --accent: #26ae60;
            --sidebar-width: 260px;
            --topbar-height: 72px;
        }
        * { box-sizing: border-box; }
        body { font-family: Inter, system-ui, sans-serif; background: var(--bg); color: var(--text); margin: 0; }
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 350, "GRAD" 0, "opsz" 24;
            font-size: 22px;
            line-height: 1;
        }
        .shell { min-height: 100vh; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: #f1f4f2;
            border-right: 1px solid var(--border);
            padding: 18px 12px 14px;
            display: flex;
            flex-direction: column;
            z-index: 20;
        }
        .brand { padding: 0 8px; margin-bottom: 24px; }
        .brand h1 {
            margin: 0;
            color: var(--primary);
            font-size: 46px;
            line-height: 0.95;
            font-family: Montserrat, Inter, system-ui, sans-serif;
            letter-spacing: -0.02em;
            max-width: 180px;
        }
        .brand p { margin: 6px 0 0; color: var(--muted); font-size: 13px; }
        .menu { display: flex; flex-direction: column; gap: 8px; flex: 1; }
        .menu a {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            color: #4e5664;
            padding: 11px 10px;
            border-radius: 8px;
            font-weight: 600;
            border-right: 4px solid transparent;
            font-size: 30px;
            letter-spacing: 0.01em;
        }
        .menu a.active {
            color: var(--primary);
            background: var(--primary-soft);
            border-right-color: var(--primary);
        }
        .menu a:hover { background: #f8fbf9; }
        .menu .material-symbols-outlined { color: #5f6674; }
        .menu a.active .material-symbols-outlined { color: var(--primary); }
        .bottom-actions { border-top: 1px solid var(--border); padding: 16px 8px 0; display: grid; gap: 10px; }
        .bottom-actions a {
            display: flex;
            align-items: center;
            gap: 11px;
            text-decoration: none;
            color: #4e5664;
            padding: 10px 2px;
            font-weight: 600;
            border-radius: 8px;
            border-right: 4px solid transparent;
        }
        .bottom-actions a.active {
            color: var(--primary);
            background: var(--primary-soft);
            border-right-color: var(--primary);
        }
        .bottom-actions a:hover { background: #f8fbf9; }
        .btn, button {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary-cta {
            background: var(--accent);
            color: #072115;
            font-weight: 700;
            text-align: center;
            width: 100%;
            border-radius: 10px;
            padding: 11px 14px;
        }
        .btn-muted {
            background: transparent;
            color: #334155;
            border: 0;
            padding: 0;
        }
        .btn-danger { background: #b91c1c; }
        .topbar {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: 0;
            height: var(--topbar-height);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px 0 28px;
            z-index: 10;
        }
        .topbar-links { display: flex; gap: 30px; }
        .topbar-links a {
            color: #1f2b38;
            text-decoration: none;
            font-weight: 600;
            font-size: 28px;
            letter-spacing: 0.01em;
        }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .search-wrap {
            position: relative;
            width: 280px;
        }
        .search-wrap input {
            width: 100%;
            border-radius: 999px;
            border: 1px solid #9fc3b1;
            background: #e6f0eb;
            padding: 8px 14px 8px 42px;
            color: #38505a;
            font-weight: 500;
        }
        .search-wrap .material-symbols-outlined {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #45606d;
            font-size: 20px;
        }
        .top-icon {
            background: transparent;
            border: 0;
            color: #355061;
            padding: 0;
            display: inline-flex;
            align-items: center;
        }
        .top-divider {
            width: 1px;
            height: 30px;
            background: var(--border);
            margin: 0 4px;
        }
        .top-logout {
            background: transparent;
            color: #1f2b38;
            border: 0;
            font-weight: 700;
            font-size: 30px;
            padding: 0;
        }
        .profile-avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #d7e4dc;
            border: 1px solid #aabdb0;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #24473f;
        }
        .main {
            margin-left: var(--sidebar-width);
            padding: calc(var(--topbar-height) + 18px) 20px 20px;
        }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        h1 { margin: 0 0 12px; font-size: 28px; }
        h2 { margin: 0 0 10px; font-size: 20px; }
        p { margin: 0 0 8px; }
        .muted { color: var(--muted); font-size: 14px; }
        label { display: block; font-weight: 600; margin: 10px 0 4px; }
        input, select { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; }
        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .status { background: #dcfce7; color: #14532d; border: 1px solid #86efac; padding: 10px; border-radius: 8px; margin-bottom: 12px; }
        .error { color: #b91c1c; font-size: 14px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        @media (max-width: 980px) {
            .sidebar { position: static; width: 100%; height: auto; border-right: 0; border-bottom: 1px solid var(--border); }
            .topbar { position: static; left: 0; }
            .main { margin-left: 0; padding-top: 18px; }
            .shell { display: block; }
            .search-wrap { width: 180px; }
        }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $role = $user?->role;
    $isAdminLike = in_array($role, ['admin', 'beheerder'], true);
    $roleLabel = $role === 'instructeur' ? 'Instructeur Portaal' : ($role === 'leerling' ? 'Leerling Portaal' : 'Admin Portaal');
    $initials = collect(explode(' ', (string) $user?->name))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
    $route = fn (string $name, string $fallback = '#') => \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
@endphp

<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <h1>Pro Rijschool</h1>
            <p>{{ $roleLabel }}</p>
        </div>

        <nav class="menu">
            @if($isAdminLike)
                <a href="{{ $route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
                <a href="{{ $route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}"><span class="material-symbols-outlined">group</span>Mijn Leerlingen</a>
                <a href="{{ $route('admin.instructors.index') }}" class="{{ request()->routeIs('admin.instructors.*') ? 'active' : '' }}"><span class="material-symbols-outlined">school</span>Instructeurs</a>
                <a href="{{ $route('admin.finance.index') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}"><span class="material-symbols-outlined">payments</span>Financien</a>
            @elseif($role === 'instructeur')
                <a href="{{ $route('instructor.dashboard') }}" class="{{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
                <a href="{{ $route('instructor.students.index') }}" class="{{ request()->routeIs('instructor.students.*') ? 'active' : '' }}"><span class="material-symbols-outlined">group</span>Mijn Leerlingen</a>
                <a href="{{ $route('instructor.planning.index') }}" class="{{ request()->routeIs('instructor.planning.*') ? 'active' : '' }}"><span class="material-symbols-outlined">calendar_month</span>Lesplanning</a>
                <a href="{{ $route('instructor.ris.index') }}" class="{{ request()->routeIs('instructor.ris.*') ? 'active' : '' }}"><span class="material-symbols-outlined">list_alt</span>RIS Modules</a>
            @elseif($role === 'leerling')
                <a href="{{ $route('learner.dashboard') }}" class="{{ request()->routeIs('learner.dashboard') ? 'active' : '' }}"><span class="material-symbols-outlined">dashboard</span>Dashboard</a>
                <a href="{{ $route('learner.planning.index') }}" class="{{ request()->routeIs('learner.planning.*') ? 'active' : '' }}"><span class="material-symbols-outlined">calendar_month</span>Planning</a>
                <a href="{{ $route('learner.progress.index') }}" class="{{ request()->routeIs('learner.progress.*') ? 'active' : '' }}"><span class="material-symbols-outlined">trending_up</span>Voortgang</a>
                <a href="{{ $route('learner.invoices.index') }}" class="{{ request()->routeIs('learner.invoices.*') ? 'active' : '' }}"><span class="material-symbols-outlined">receipt_long</span>Facturen</a>
                <a href="{{ $route('learner.theory.index') }}" class="{{ request()->routeIs('learner.theory.*') ? 'active' : '' }}"><span class="material-symbols-outlined">menu_book</span>Theorie</a>
            @endif
        </nav>

        @if($role !== 'leerling')
            <div class="bottom-actions">
                @if($role === 'instructeur')
                    <a class="btn-primary-cta" href="{{ $route('instructor.planning.index') }}">+ Nieuwe Les Inplannen</a>
                @elseif($isAdminLike)
                    <a class="btn-primary-cta" href="{{ $route('admin.students.index') }}">+ Nieuwe Les Inplannen</a>
                @endif
                <a href="{{ $isAdminLike ? $route('admin.settings.index') : $route('instructor.settings.index') }}" class="{{ request()->routeIs('*.settings.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span>Instellingen
                </a>
            </div>
        @endif
    </aside>

    <header class="topbar">
        <div class="topbar-links">
            <a href="#">Snelkoppelingen</a>
            <a href="#">Handleiding</a>
        </div>
        <div class="topbar-right">
            <div class="search-wrap">
                <span class="material-symbols-outlined">search</span>
                <input type="text" placeholder="Zoeken...">
            </div>
            <button class="top-icon" type="button" aria-label="Meldingen">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="top-icon" type="button" aria-label="Help">
                <span class="material-symbols-outlined">help</span>
            </button>
            <div class="top-divider"></div>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="top-logout">Uitloggen</button>
            </form>
            <div class="profile-avatar">{{ $initials }}</div>
        </div>
    </header>

    <main class="main">
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="card">
                <h2>Er ging iets mis</h2>
                @foreach($errors->all() as $error)
                    <p class="error">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
