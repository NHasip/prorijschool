<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pro Rijschool Portal' }}</title>
    <style>
        body { font-family: Inter, system-ui, sans-serif; background: #f1f5f9; color: #0f172a; margin: 0; }
        .wrap { max-width: 900px; margin: 32px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #dbe3ea; border-radius: 12px; padding: 20px; margin-bottom: 16px; }
        h1 { margin: 0 0 12px; font-size: 28px; }
        h2 { margin: 0 0 10px; font-size: 20px; }
        p { margin: 0 0 8px; }
        label { display: block; font-weight: 600; margin: 10px 0 4px; }
        input, select { width: 100%; box-sizing: border-box; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; }
        button, .btn { display: inline-block; background: #006d37; color: white; border: 0; border-radius: 8px; padding: 10px 14px; text-decoration: none; font-weight: 600; cursor: pointer; }
        .btn-muted { background: #334155; }
        .btn-danger { background: #b91c1c; }
        .row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .error { color: #b91c1c; font-size: 14px; margin-top: 4px; }
        .status { background: #dcfce7; color: #14532d; border: 1px solid #86efac; padding: 10px; border-radius: 8px; margin-bottom: 12px; }
        .muted { color: #475569; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
    </style>
</head>
<body>
<main class="wrap">
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
