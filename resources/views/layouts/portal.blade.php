<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pro Rijschool Portal' }}</title>
    <style>
        :root {
            --bg: #f1f5f9;
            --surface: #ffffff;
            --border: #dbe3ea;
            --text: #0f172a;
            --muted: #475569;
            --primary: #006d37;
            --sidebar-width: 260px;
            --topbar-height: 64px;
        }
        * { box-sizing: border-box; }
        body { font-family: Inter, system-ui, sans-serif; background: var(--bg); color: var(--text); margin: 0; }
        .shell { min-height: 100vh; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 24px 12px;
            display: flex;
            flex-direction: column;
            z-index: 20;
        }
        .brand { padding: 0 8px; margin-bottom: 24px; }
        .brand h1 { margin: 0; color: var(--primary); font-size: 34px; line-height: 1.1; }
        .brand p { margin: 6px 0 0; color: var(--muted); font-size: 13px; }
        .menu { display: flex; flex-direction: column; gap: 6px; flex: 1; }
        .menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--muted);
            padding: 11px 10px;
            border-radius: 8px;
            font-weight: 600;
            border-right: 4px solid transparent;
        }
        .menu a.active {
            color: var(--primary);
            background: #eef6f2;
            border-right-color: var(--primary);
        }
        .menu a:hover { background: #f7fafc; }
        .bottom-actions { border-top: 1px solid var(--border); padding: 16px 8px 0; }
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
        .btn-muted { background: #334155; }
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
            padding: 0 24px;
            z-index: 10;
        }
        .topbar-links { display: flex; gap: 24px; }
        .topbar-links a { color: var(--text); text-decoration: none; font-weight: 600; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
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
        }
    </style>
</head>
<body>
@php
    $user = auth()->user();
    $role = $user?->role;
    $isAdminLike = in_array($role, ['admin', 'beheerder'], true);
    $roleLabel = $role === 'instructeur' ? 'Instructeur Portaal' : ($role === 'leerling' ? 'Leerling Portaal' : 'Admin Portaal');
@endphp

<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <h1>Pro Rijschool</h1>
            <p>{{ $roleLabel }}</p>
        </div>

        <nav class="menu">
            @if($isAdminLike)
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">Mijn Leerlingen</a>
                <a href="{{ route('admin.instructors.index') }}" class="{{ request()->routeIs('admin.instructors.*') ? 'active' : '' }}">Instructeurs</a>
                <a href="{{ route('admin.finance.index') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">Financien</a>
                <a href="{{ route('admin.approvals.index') }}" class="{{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">Goedkeuringen</a>
            @elseif($role === 'instructeur')
                <a href="{{ route('instructor.dashboard') }}" class="{{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('instructor.students.index') }}" class="{{ request()->routeIs('instructor.students.*') ? 'active' : '' }}">Mijn Leerlingen</a>
                <a href="{{ route('instructor.planning.index') }}" class="{{ request()->routeIs('instructor.planning.*') ? 'active' : '' }}">Lesplanning</a>
                <a href="{{ route('instructor.ris.index') }}" class="{{ request()->routeIs('instructor.ris.*') ? 'active' : '' }}">RIS Modules</a>
                <a href="{{ route('instructor.settings.index') }}" class="{{ request()->routeIs('instructor.settings.*') ? 'active' : '' }}">Instellingen</a>
            @elseif($role === 'leerling')
                <a href="{{ route('learner.dashboard') }}" class="{{ request()->routeIs('learner.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('learner.planning.index') }}" class="{{ request()->routeIs('learner.planning.*') ? 'active' : '' }}">Planning</a>
                <a href="{{ route('learner.progress.index') }}" class="{{ request()->routeIs('learner.progress.*') ? 'active' : '' }}">Voortgang</a>
                <a href="{{ route('learner.invoices.index') }}" class="{{ request()->routeIs('learner.invoices.*') ? 'active' : '' }}">Facturen</a>
                <a href="{{ route('learner.theory.index') }}" class="{{ request()->routeIs('learner.theory.*') ? 'active' : '' }}">Theorie</a>
            @endif
        </nav>

        @if($role !== 'leerling')
            <div class="bottom-actions">
                @if($role === 'instructeur')
                    <a class="btn" href="{{ route('instructor.planning.index') }}">Nieuwe Les Inplannen</a>
                @elseif($isAdminLike)
                    <a class="btn" href="{{ route('admin.students.index') }}">Beheer Leerlingen</a>
                @endif
            </div>
        @endif
    </aside>

    <header class="topbar">
        <div class="topbar-links">
            <a href="#">Snelkoppelingen</a>
            <a href="#">Handleiding</a>
        </div>
        <div class="topbar-right">
            <span class="muted" style="margin: 0;">{{ $user?->name }}</span>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-muted">Uitloggen</button>
            </form>
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

