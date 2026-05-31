<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pro Rijschool Portal' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                "outline-variant": "#bccabc",
                "error": "#ba1a1a",
                "primary-fixed": "#7efba4",
                "tertiary-fixed-dim": "#c4c7c5",
                "secondary-fixed": "#e5e2e1",
                "on-background": "#121e1f",
                "on-secondary": "#ffffff",
                "inverse-primary": "#61de8a",
                "surface": "#eefcfd",
                "on-surface-variant": "#3d4a3f",
                "primary": "#006d37",
                "on-primary-container": "#00391a",
                "primary-fixed-dim": "#61de8a",
                "secondary-fixed-dim": "#c8c6c5",
                "primary-container": "#27ae60",
                "on-primary": "#ffffff",
                "surface-container-low": "#e9f6f7",
                "surface-container-high": "#ddebec",
                "surface-container": "#e3f0f1",
                "on-secondary-fixed-variant": "#474746",
                "on-primary-fixed": "#00210c",
                "surface-variant": "#d8e5e6",
                "on-surface": "#121e1f",
                "tertiary-fixed": "#e0e3e1",
                "on-tertiary-fixed": "#181c1b",
                "secondary-container": "#e2dfde",
                "on-primary-fixed-variant": "#005228",
                "on-tertiary-container": "#2d3130",
                "on-error-container": "#93000a",
                "surface-tint": "#006d37",
                "tertiary-container": "#959998",
                "on-error": "#ffffff",
                "outline": "#6d7a6e",
                "surface-container-highest": "#d8e5e6",
                "on-secondary-fixed": "#1c1b1b",
                "surface-container-lowest": "#ffffff",
                "on-tertiary": "#ffffff",
                "on-secondary-container": "#636262",
                "inverse-on-surface": "#e6f3f4",
                "tertiary": "#5b5f5e",
                "surface-dim": "#cfdddd",
                "inverse-surface": "#263334",
                "on-tertiary-fixed-variant": "#434846",
                "surface-bright": "#eefcfd",
                "error-container": "#ffdad6",
                "background": "#eefcfd",
                "secondary": "#5f5e5e"
              },
              "borderRadius": {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
              },
              "spacing": {
                "md": "24px",
                "container-max": "1200px",
                "sm": "12px",
                "gutter": "24px",
                "xs": "4px",
                "lg": "48px",
                "xl": "80px",
                "base": "8px"
              },
              "fontFamily": {
                "body-md": ["Inter"],
                "body-lg": ["Inter"],
                "display-lg": ["Montserrat"],
                "headline-md-mobile": ["Montserrat"],
                "display-lg-mobile": ["Montserrat"],
                "headline-sm": ["Montserrat"],
                "headline-md": ["Montserrat"],
                "label-sm": ["Inter"],
                "label-md": ["Inter"]
              },
              "fontSize": {
                "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                "display-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                "headline-md-mobile": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                "display-lg-mobile": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                "headline-md": ["32px", {"lineHeight": "1.25", "fontWeight": "600"}],
                "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "600"}]
              }
            }
          }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .portal-main .card {
            background: #ffffff;
            border: 1px solid #bccabc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }
        .portal-main h1 {
            margin: 0 0 12px;
            font-size: 42px;
            line-height: 1.2;
            font-weight: 600;
            font-family: Montserrat, Inter, sans-serif;
            color: #121e1f;
        }
        .portal-main h2 {
            margin: 0 0 10px;
            font-size: 24px;
            line-height: 1.3;
            font-weight: 600;
            font-family: Montserrat, Inter, sans-serif;
            color: #121e1f;
        }
        .portal-main p { margin: 0 0 8px; }
        .portal-main .muted { color: #5f5e5e; font-size: 16px; line-height: 1.5; }
        .portal-main label { display: block; font-weight: 600; margin: 10px 0 4px; }
        .portal-main input, .portal-main select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #bccabc;
            background: #fff;
        }
        .portal-main button, .portal-main .btn {
            display: inline-block;
            background: #006d37;
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }
        .portal-main .btn-muted { background: #334155; }
        .portal-main .btn-danger { background: #b91c1c; }
        .portal-main .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .portal-main .status {
            background: #dcfce7;
            color: #14532d;
            border: 1px solid #86efac;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .portal-main .error { color: #b91c1c; font-size: 14px; margin-top: 4px; }
        .portal-main table { width: 100%; border-collapse: collapse; }
        .portal-main th, .portal-main td {
            padding: 10px;
            border-bottom: 1px solid #d8e5e6;
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>
<body class="text-on-background font-body-md antialiased bg-[#eef4f3]">
@php
    $user = auth()->user();
    $role = $user?->role;
    $isAdminLike = in_array($role, ['admin', 'beheerder'], true);
    $roleLabel = $role === 'instructeur' ? 'Instructeur Portaal' : ($role === 'leerling' ? 'Leerling Portaal' : 'Admin Portaal');
    $route = fn (string $name, string $fallback = '#') => \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;

    $items = $isAdminLike
        ? [
            ['label' => 'Dashboard', 'icon' => 'dashboard', 'href' => $route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => 'Mijn Leerlingen', 'icon' => 'group', 'href' => $route('admin.students.index'), 'active' => request()->routeIs('admin.students.*')],
            ['label' => 'Instructeurs', 'icon' => 'school', 'href' => $route('admin.instructors.index'), 'active' => request()->routeIs('admin.instructors.*')],
            ['label' => 'Financien', 'icon' => 'payments', 'href' => $route('admin.finance.index'), 'active' => request()->routeIs('admin.finance.*')],
        ]
        : ($role === 'instructeur'
            ? [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'href' => $route('instructor.dashboard'), 'active' => request()->routeIs('instructor.dashboard')],
                ['label' => 'Mijn Leerlingen', 'icon' => 'group', 'href' => $route('instructor.students.index'), 'active' => request()->routeIs('instructor.students.*')],
                ['label' => 'Lesplanning', 'icon' => 'calendar_month', 'href' => $route('instructor.planning.index'), 'active' => request()->routeIs('instructor.planning.*')],
                ['label' => 'RIS Modules', 'icon' => 'list_alt', 'href' => $route('instructor.ris.index'), 'active' => request()->routeIs('instructor.ris.*')],
            ]
            : [
                ['label' => 'Dashboard', 'icon' => 'dashboard', 'href' => $route('learner.dashboard'), 'active' => request()->routeIs('learner.dashboard')],
                ['label' => 'Planning', 'icon' => 'calendar_month', 'href' => $route('learner.planning.index'), 'active' => request()->routeIs('learner.planning.*')],
                ['label' => 'Voortgang', 'icon' => 'trending_up', 'href' => $route('learner.progress.index'), 'active' => request()->routeIs('learner.progress.*')],
                ['label' => 'Facturen', 'icon' => 'receipt_long', 'href' => $route('learner.invoices.index'), 'active' => request()->routeIs('learner.invoices.*')],
                ['label' => 'Theorie', 'icon' => 'menu_book', 'href' => $route('learner.theory.index'), 'active' => request()->routeIs('learner.theory.*')],
            ]);
@endphp

<aside class="h-screen w-64 fixed left-0 top-0 border-r border-outline-variant flex flex-col py-md px-sm z-50 bg-white">
    <div class="mb-lg px-sm">
        <h1 class="font-headline-md text-headline-md font-bold text-primary">Pro<br>Rijschool</h1>
        <p class="font-label-sm text-label-sm text-secondary mt-xs">{{ $roleLabel }}</p>
    </div>

    <nav class="flex-1 flex flex-col gap-xs">
        @foreach($items as $item)
            <a
                class="flex items-center gap-sm py-sm px-sm rounded-lg {{ $item['active'] ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-lowest opacity-80 duration-150' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150' }}"
                href="{{ $item['href'] }}"
            >
                <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                <span class="font-label-md text-label-md">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    @if($role !== 'leerling')
        <div class="mt-auto border-t border-outline-variant pt-md px-sm">
            <a class="w-full bg-primary-container text-on-primary-container font-label-md text-label-md py-sm rounded-lg flex items-center justify-center gap-xs hover:opacity-90 transition-opacity"
               href="{{ $role === 'instructeur' ? $route('instructor.planning.index') : $route('admin.students.index') }}">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Nieuwe Les Inplannen
            </a>
            <a class="mt-sm flex items-center gap-sm py-sm rounded-lg {{ request()->routeIs('*.settings.*') ? 'text-primary font-bold' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150' }}"
               href="{{ $isAdminLike ? $route('admin.settings.index') : $route('instructor.settings.index') }}">
                <span class="material-symbols-outlined text-[20px]">settings</span>
                <span class="font-label-md text-label-md">Instellingen</span>
            </a>
        </div>
    @endif
</aside>

<header class="fixed top-0 right-0 w-[calc(100%-16rem)] z-40 border-b border-outline-variant flex justify-between items-center h-16 px-lg ml-64 transition-all bg-white">
    <div class="flex items-center gap-4">
        <span class="font-headline-sm text-headline-sm font-bold text-primary">Momentum Drive</span>
        <nav class="hidden lg:flex items-center gap-6 ml-lg">
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all scale-100 hover:scale-95 duration-100" href="#">Snelkoppelingen</a>
            <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all scale-100 hover:scale-95 duration-100" href="#">Handleiding</a>
        </nav>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative hidden md:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input class="pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded-full text-label-md focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container w-64 transition-all" placeholder="Zoeken..." type="text">
        </div>
        <div class="flex items-center gap-3">
            <button class="text-on-surface-variant hover:text-primary transition-colors relative" type="button">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
            </button>
            <button class="text-on-surface-variant hover:text-primary transition-colors" type="button">
                <span class="material-symbols-outlined">help_outline</span>
            </button>
        </div>
        <div class="flex items-center gap-4 pl-4 border-l border-outline-variant">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="font-label-md text-label-md text-error hover:text-on-error-container transition-colors" type="submit">Uitloggen</button>
            </form>
            <img alt="Gebruiker" class="w-8 h-8 rounded-full border border-outline-variant" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCbVKDjd8c9ErPmLWdkASvzU-MdJa5IQvqkux6FcJe1eNt3w4jSOhu_mXdbctpmkHIgmABW2F3DQDhVMiet--rp5Gnf4IhegMpH8h_LaL0XlMlENDqKOlTmLLJXSguFpN_PIDNicF-ANw1HM_WnyiBDpr4js9H3TI0oc_y3w5ViyiLeePPs14ww-5j16oa6BAPcsEc8eKLU-9ol6Uv4TB5HKdIW5ZLgZaVCTSOZgtjBA-TlhlrHPS0nGtkd-jiZ5lDBSvOAmSmJNdo">
        </div>
    </div>
</header>

<main class="portal-main ml-64 pt-16 min-h-screen p-lg lg:p-xl">
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
</body>
</html>
