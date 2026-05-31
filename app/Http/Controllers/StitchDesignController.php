<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
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

    public function show(string $screenId): Response
    {
        $htmlPath = $this->findHtmlPathByScreenId($screenId);

        if (! is_string($htmlPath) || ! is_file($htmlPath)) {
            throw new NotFoundHttpException("No HTML export found for screen {$screenId}.");
        }

        $html = file_get_contents($htmlPath);

        return response($html ?: '', 200, [
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
}
