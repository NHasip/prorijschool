<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StitchDesignController extends Controller
{
    private string $manifestPath;
    private string $htmlDir;
    private string $screenshotDir;

    public function __construct()
    {
        $this->manifestPath = resource_path('stitch-export/manifest.json');
        $this->htmlDir = resource_path('stitch-export/html');
        $this->screenshotDir = resource_path('stitch-export/screenshots');
    }

    public function index(): View
    {
        return view('stitch.index', [
            'screens' => $this->loadManifest(),
        ]);
    }

    public function show(Request $request, string $screenId): Response
    {
        $htmlPath = $this->findHtmlPathByScreenId($screenId);

        if (! is_string($htmlPath) || ! is_file($htmlPath)) {
            throw new NotFoundHttpException("No HTML export found for screen {$screenId}.");
        }

        $html = file_get_contents($htmlPath) ?: '';
        $html = $this->injectInteractionBridge($html, $request);

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    public function screenshot(string $screenId): Response
    {
        $screenshotPath = $this->findScreenshotPathByScreenId($screenId);

        if (! is_string($screenshotPath) || ! is_file($screenshotPath)) {
            throw new NotFoundHttpException("No screenshot found for screen {$screenId}.");
        }

        return response()->file($screenshotPath);
    }

    private function loadManifest(): array
    {
        if (! is_file($this->manifestPath)) {
            return [];
        }

        $json = file_get_contents($this->manifestPath);
        $screens = json_decode($json ?: '[]', true);

        if (! is_array($screens)) {
            return [];
        }

        $normalized = array_values(array_filter($screens, fn ($item) => is_array($item)));

        foreach ($normalized as &$screen) {
            $screen['html'] = $this->normalizePath($screen['html'] ?? null, $this->htmlDir);
            $screen['screenshot'] = $this->normalizePath($screen['screenshot'] ?? null, $this->screenshotDir);
        }
        unset($screen);

        return $normalized;
    }

    private function findScreen(string $screenId): array
    {
        foreach ($this->loadManifest() as $screen) {
            if (($screen['screenId'] ?? null) === $screenId) {
                return $screen;
            }
        }

        throw new NotFoundHttpException("Screen {$screenId} was not found in Stitch export.");
    }

    private function findHtmlPathByScreenId(string $screenId): ?string
    {
        foreach ($this->loadManifest() as $screen) {
            if (($screen['screenId'] ?? null) === $screenId) {
                $html = Arr::get($screen, 'html');
                if (is_string($html) && is_file($html)) {
                    return $html;
                }
                break;
            }
        }

        $matches = glob($this->htmlDir.DIRECTORY_SEPARATOR.'*'.$screenId.'.html') ?: [];
        return $matches[0] ?? null;
    }

    private function findScreenshotPathByScreenId(string $screenId): ?string
    {
        foreach ($this->loadManifest() as $screen) {
            if (($screen['screenId'] ?? null) === $screenId) {
                $shot = Arr::get($screen, 'screenshot');
                if (is_string($shot) && is_file($shot)) {
                    return $shot;
                }
                break;
            }
        }

        $matches = glob($this->screenshotDir.DIRECTORY_SEPARATOR.'*'.$screenId.'.png') ?: [];
        return $matches[0] ?? null;
    }

    private function normalizePath(?string $path, string $baseDir): ?string
    {
        if (! is_string($path) || trim($path) === '') {
            return null;
        }

        if (is_file($path)) {
            return $path;
        }

        $filename = basename(str_replace('\\', '/', $path));
        $candidate = $baseDir.DIRECTORY_SEPARATOR.$filename;

        return is_file($candidate) ? $candidate : null;
    }

    private function injectInteractionBridge(string $html, Request $request): string
    {
        $csrf = csrf_token();
        $routes = [
            'dashboard' => route('dashboard'),
            'logout' => route('logout'),
            'admin_students' => route('admin.students.index'),
            'admin_instructors' => route('admin.instructors.index'),
            'admin_finance' => route('admin.finance.index'),
            'admin_settings' => route('admin.settings.index'),
            'approvals' => route('admin.approvals.index'),
        ];

        $routeJson = json_encode($routes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $isAdminLike = $request->user()?->isRole('admin', 'beheerder') ? 'true' : 'false';

        $script = <<<HTML
<script>
(function () {
  const routes = {$routeJson};
  const isAdminLike = {$isAdminLike};

  function postLogout() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = routes.logout;
    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.appendChild(token);
    document.body.appendChild(form);
    form.submit();
  }

  function routeForLabel(text) {
    const t = (text || '').trim().toLowerCase();
    if (!t) return null;
    if (t.includes('uitloggen')) return '__logout__';
    if (t.includes('dashboard')) return routes.dashboard;
    if (t.includes('mijn leerlingen') || t.includes('leerlingen beheer')) return isAdminLike ? routes.admin_students : routes.dashboard;
    if (t.includes('instructeurs')) return isAdminLike ? routes.admin_instructors : routes.dashboard;
    if (t.includes('financi')) return isAdminLike ? routes.admin_finance : routes.dashboard;
    if (t.includes('instellingen')) return isAdminLike ? routes.admin_settings : routes.dashboard;
    if (t.includes('goedkeuren')) return isAdminLike ? routes.approvals : routes.dashboard;
    if (t.includes('lesplanning')) return routes.dashboard;
    if (t.includes('ris modules')) return routes.dashboard;
    if (t.includes('snelkoppelingen') || t.includes('handleiding')) return routes.dashboard;
    return null;
  }

  document.addEventListener('click', function (event) {
    const target = event.target.closest('a, button, span, div');
    if (!target) return;
    const destination = routeForLabel(target.textContent);
    if (!destination) return;
    event.preventDefault();
    event.stopPropagation();
    if (destination === '__logout__') {
      postLogout();
      return;
    }
    window.location.href = destination;
  }, true);
})();
</script>
HTML;

        if (str_contains($html, 'name="csrf-token"')) {
            $htmlWithMeta = $html;
        } elseif (str_contains($html, '</head>')) {
            $htmlWithMeta = str_replace('</head>', '<meta name="csrf-token" content="'.$csrf.'"></head>', $html);
        } else {
            $htmlWithMeta = '<meta name="csrf-token" content="'.$csrf.'">'.$html;
        }

        if (str_contains($htmlWithMeta, '</body>')) {
            return str_replace('</body>', $script.'</body>', $htmlWithMeta);
        }

        return $htmlWithMeta.$script;
    }
}
