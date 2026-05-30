<?php

use App\Http\Controllers\StitchDesignController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StitchDesignController::class, 'index']);

Route::get('/stitch', [StitchDesignController::class, 'index'])->name('stitch.index');
Route::get('/stitch/{screenId}', [StitchDesignController::class, 'show'])->name('stitch.show');
Route::get('/stitch/{screenId}/screenshot', [StitchDesignController::class, 'screenshot'])->name('stitch.screenshot');
