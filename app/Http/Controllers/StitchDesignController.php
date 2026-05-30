<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StitchDesignController extends Controller
{
    private string $manifestPath;

    public function __construct()
    {
        $this->manifestPath = resource_path('stitch-export/manifest.json');
    }

    public function index(): View
    {
        return view('stitch.index', [
            'screens' => $this->loadManifest(),
        ]);
    }

    public function show(string $screenId): Response
    {
        $screen = $this->findScreen($screenId);
        $htmlPath = Arr::get($screen, 'html');

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
        $screen = $this->findScreen($screenId);
        $screenshotPath = Arr::get($screen, 'screenshot');

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

        return array_values(array_filter($screens, fn ($item) => is_array($item)));
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
}
