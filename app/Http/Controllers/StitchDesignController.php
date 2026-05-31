<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
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
        $role = (string) ($request->user()?->role ?? '');
        $routes = [
            'logout' => $this->routeOrNull('logout'),
            'root_dashboard' => $this->routeOrNull('dashboard'),

            'admin_dashboard' => $this->routeOrNull('admin.dashboard'),
            'admin_students' => $this->routeOrNull('admin.students.index'),
            'admin_instructors' => $this->routeOrNull('admin.instructors.index'),
            'admin_finance' => $this->routeOrNull('admin.finance.index'),
            'admin_settings' => $this->routeOrNull('admin.settings.index'),
            'admin_approvals' => $this->routeOrNull('admin.approvals.index'),

            'instructor_dashboard' => $this->routeOrNull('instructor.dashboard'),
            'instructor_students' => $this->routeOrNull('instructor.students.index'),
            'instructor_planning' => $this->routeOrNull('instructor.planning.index'),
            'instructor_ris' => $this->routeOrNull('instructor.ris.index'),
            'instructor_settings' => $this->routeOrNull('instructor.settings.index'),

            'learner_dashboard' => $this->routeOrNull('learner.dashboard'),
            'learner_planning' => $this->routeOrNull('learner.planning.index'),
            'learner_progress' => $this->routeOrNull('learner.progress.index'),
            'learner_progress_detail' => $this->routeOrNull('learner.progress.detail'),
            'learner_invoices' => $this->routeOrNull('learner.invoices.index'),
            'learner_theory' => $this->routeOrNull('learner.theory.index'),
        ];

        $routeJson = json_encode($routes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $roleJson = json_encode($role, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $script = <<<HTML
<script>
(function () {
  const routes = {$routeJson};
  const role = {$roleJson};

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

  function includesAny(text, terms) {
    return terms.some((term) => text.includes(term));
  }

  function roleDashboard() {
    if (role === 'admin' || role === 'beheerder') return routes.admin_dashboard;
    if (role === 'instructeur') return routes.instructor_dashboard;
    if (role === 'leerling') return routes.learner_dashboard;
    return routes.root_dashboard;
  }

  function routeOrHash(url) {
    return url || '#';
  }

  function normalizeInstructorChrome() {
    if (role !== 'instructeur') return;

    const nav = document.querySelector('nav');
    const header = document.querySelector('header');
    if (!nav || !header) return;

    const path = window.location.pathname;
    const isActive = (segment) => path.includes(segment);

    nav.className = 'h-screen w-64 fixed left-0 top-0 border-r border-outline-variant flex flex-col py-md px-sm z-50 bg-white';

    nav.innerHTML = `
<div class="mb-lg px-sm">
  <h1 class="font-headline-md text-headline-md font-bold text-primary">Momentum</h1>
  <p class="font-label-sm text-label-sm text-secondary mt-xs">Instructeur Portaal</p>
</div>
<ul class="flex flex-col gap-xs flex-1">
  <li>
    <a class="flex items-center gap-sm py-sm px-sm rounded-lg ${isActive('/instructeur/dashboard') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-lowest opacity-80 duration-150' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150'}" href="${routeOrHash(routes.instructor_dashboard)}">
      <span class="material-symbols-outlined text-[20px]">dashboard</span>
      <span class="font-label-md text-label-md">Dashboard</span>
    </a>
  </li>
  <li>
    <a class="flex items-center gap-sm py-sm px-sm rounded-lg ${isActive('/instructeur/leerlingen') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-lowest opacity-80 duration-150' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150'}" href="${routeOrHash(routes.instructor_students)}">
      <span class="material-symbols-outlined text-[20px]">group</span>
      <span class="font-label-md text-label-md">Mijn Leerlingen</span>
    </a>
  </li>
  <li>
    <a class="flex items-center gap-sm py-sm px-sm rounded-lg ${isActive('/instructeur/lesplanning') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-lowest opacity-80 duration-150' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150'}" href="${routeOrHash(routes.instructor_planning)}">
      <span class="material-symbols-outlined text-[20px]">calendar_month</span>
      <span class="font-label-md text-label-md">Lesplanning</span>
    </a>
  </li>
  <li>
    <a class="flex items-center gap-sm py-sm px-sm rounded-lg ${isActive('/instructeur/ris-modules') ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-lowest opacity-80 duration-150' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150'}" href="${routeOrHash(routes.instructor_ris)}">
      <span class="material-symbols-outlined text-[20px]">list_alt</span>
      <span class="font-label-md text-label-md">RIS Modules</span>
    </a>
  </li>
</ul>
<div class="mt-auto border-t border-outline-variant pt-md px-sm">
  <button class="w-full bg-primary-container text-on-primary-container font-label-md text-label-md py-sm rounded-lg flex items-center justify-center gap-xs hover:opacity-90 transition-opacity">
    <span class="material-symbols-outlined text-[18px]">add</span>
    Nieuwe Les Inplannen
  </button>
  <a class="mt-sm flex items-center gap-sm py-sm rounded-lg ${isActive('/instructeur/instellingen') ? 'text-primary font-bold' : 'text-secondary hover:bg-surface-container-low transition-colors duration-150'}" href="${routeOrHash(routes.instructor_settings)}">
    <span class="material-symbols-outlined text-[20px]">settings</span>
    <span class="font-label-md text-label-md">Instellingen</span>
  </a>
</div>`;

    header.className = 'fixed top-0 right-0 w-[calc(100%-16rem)] z-40 border-b border-outline-variant flex justify-between items-center h-16 px-lg bg-white';
    header.innerHTML = `
<div class="flex gap-md">
  <a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-all" href="#">Snelkoppelingen</a>
  <a class="text-on-surface-variant font-label-md text-label-md hover:text-primary transition-all" href="#">Handleiding</a>
</div>
<div class="flex items-center gap-md">
  <div class="relative hidden lg:block">
    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-secondary text-[18px]">search</span>
    <input class="pl-xl pr-sm py-xs bg-surface-container-low border border-outline-variant rounded-full text-label-sm focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all w-48" placeholder="Zoeken..." type="text">
  </div>
  <button class="text-secondary hover:text-primary transition-all hover:scale-95 duration-100 flex items-center">
    <span class="material-symbols-outlined">notifications</span>
  </button>
  <button class="text-secondary hover:text-primary transition-all hover:scale-95 duration-100 flex items-center">
    <span class="material-symbols-outlined">help_outline</span>
  </button>
  <div class="w-[1px] h-6 bg-outline-variant mx-xs"></div>
  <button class="font-label-md text-label-md text-secondary hover:text-primary transition-all">Uitloggen</button>
  <div class="w-8 h-8 rounded-full bg-secondary-container overflow-hidden border border-outline-variant">
    <img alt="Instructeur Profielfoto" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_PEO-hz0FdzAU7XPdf7U7SAEXE-BPQwN7Zx13R8cq0QZiSAOgN9g1x201LE_s-lx6yLW_HwMJLka2OS2gtD-JBtWHhQT_Ss7AMk6M8NqzsL_EWOzBUk5NVJaHEg3qut0TbEMpO-ObML9l6ZCfFy6WXwIYmougUaQEY3-00v11q_ypOhxDxIZFZBy5EKwf9MesXbxH8iOcNt70Nlk5PSh6FA56HJmnScwJkFdosu-csBweONBDOCvMdoMHwY8QZ-CKwZ5OZbazYU4">
  </div>
</div>`;
  }

  function routeForLabel(text) {
    const t = (text || '').trim().toLowerCase();
    if (!t) return null;

    if (includesAny(t, ['uitloggen', 'logout'])) return '__logout__';
    if (includesAny(t, ['dashboard', 'overzicht'])) return roleDashboard();

    if (role === 'admin' || role === 'beheerder') {
      if (includesAny(t, ['mijn leerlingen', 'leerlingen beheer', 'leerlingen'])) return routes.admin_students;
      if (includesAny(t, ['instructeurs'])) return routes.admin_instructors;
      if (includesAny(t, ['financi', 'facturen', 'omzet'])) return routes.admin_finance;
      if (includesAny(t, ['instellingen'])) return routes.admin_settings;
      if (includesAny(t, ['goedkeuren'])) return routes.admin_approvals;
      if (includesAny(t, ['lesplanning', 'ris modules', 'snelkoppelingen', 'handleiding'])) return routes.admin_dashboard;
      return null;
    }

    if (role === 'instructeur') {
      if (includesAny(t, ['mijn leerlingen', 'leerlingen'])) return routes.instructor_students;
      if (includesAny(t, ['lesplanning', 'nieuwe les inplannen', 'planning'])) return routes.instructor_planning;
      if (includesAny(t, ['ris modules', 'voortgang'])) return routes.instructor_ris;
      if (includesAny(t, ['instellingen'])) return routes.instructor_settings;
      if (includesAny(t, ['snelkoppelingen', 'handleiding'])) return routes.instructor_dashboard;
      return null;
    }

    if (role === 'leerling') {
      if (includesAny(t, ['planning', 'rooster'])) return routes.learner_planning;
      if (includesAny(t, ['voortgang'])) return routes.learner_progress;
      if (includesAny(t, ['facturen', 'betalingen'])) return routes.learner_invoices;
      if (includesAny(t, ['theorie'])) return routes.learner_theory;
      if (includesAny(t, ['details'])) return routes.learner_progress_detail;
      return null;
    }

    return null;
  }

  normalizeInstructorChrome();

  document.addEventListener('click', function (event) {
    const target = event.target.closest('a, button');
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

    private function routeOrNull(string $name): ?string
    {
        if (! Route::has($name)) {
            return null;
        }

        return route($name);
    }
}
