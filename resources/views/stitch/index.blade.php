<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stitch Design Baseline</title>
    <style>
        body {
            margin: 0;
            font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #f2f5f7;
            color: #0f172a;
        }

        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .title {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .subtitle {
            margin: 0 0 24px;
            color: #334155;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .card {
            background: #fff;
            border: 1px solid #d9e2ec;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        }

        .preview {
            width: 100%;
            aspect-ratio: 16 / 10;
            object-fit: cover;
            display: block;
            background: #e2e8f0;
        }

        .meta {
            padding: 12px;
        }

        .meta h2 {
            margin: 0 0 6px;
            font-size: 16px;
        }

        .meta p {
            margin: 0 0 10px;
            color: #475569;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-block;
            padding: 8px 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-primary {
            background: #006d37;
            color: #fff;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>
<main class="page">
    <h1 class="title">Stitch Design Baseline</h1>
    <p class="subtitle">Overzicht van geëxporteerde schermen uit <strong>Necmar Pro Rijschool Platform</strong>.</p>

    <section class="grid">
        @foreach($screens as $screen)
            @php
                $screenId = $screen['screenId'] ?? '';
                $title = $screen['title'] ?? $screenId;
                $hasHtml = !empty($screen['html']) && is_file($screen['html']);
            @endphp
            <article class="card">
                <img class="preview"
                     src="{{ route('stitch.screenshot', ['screenId' => $screenId]) }}"
                     alt="{{ $title }}">
                <div class="meta">
                    <h2>{{ $title }}</h2>
                    <p>{{ $screenId }}</p>
                    <div class="actions">
                        @if($hasHtml)
                            <a class="btn btn-primary" href="{{ route('stitch.show', ['screenId' => $screenId]) }}" target="_blank" rel="noopener">Open HTML</a>
                        @endif
                        <a class="btn btn-secondary" href="{{ route('stitch.screenshot', ['screenId' => $screenId]) }}" target="_blank" rel="noopener">Open screenshot</a>
                    </div>
                </div>
            </article>
        @endforeach
    </section>
</main>
</body>
</html>
