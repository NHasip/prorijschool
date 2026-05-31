<?php

use App\Http\Controllers\Admin\AdminModuleController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StitchDesignController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
    Route::get('/registreren', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registreren', [AuthController::class, 'register'])->name('register.store');

    Route::get('/2fa', [TwoFactorController::class, 'showChallenge'])->name('2fa.challenge');
    Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/opnieuw', [TwoFactorController::class, 'resend'])->name('2fa.resend');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'approved', '2fa.verified'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/2fa/toggle', [TwoFactorController::class, 'toggle'])->name('2fa.toggle');

    Route::middleware('role:admin,beheerder')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminModuleController::class, 'dashboard'])->name('dashboard');
        Route::get('/leerlingen', [AdminModuleController::class, 'students'])->name('students.index');
        Route::get('/instructeurs', [AdminModuleController::class, 'instructors'])->name('instructors.index');
        Route::get('/financien', [AdminModuleController::class, 'finance'])->name('finance.index');
        Route::get('/instellingen', [AdminModuleController::class, 'settings'])->name('settings.index');

        Route::get('/leerlingen-goedkeuring', [UserApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/leerlingen/{user}/approve', [UserApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/leerlingen/{user}/reject', [UserApprovalController::class, 'reject'])->name('approvals.reject');
    });
});

Route::get('/stitch', [StitchDesignController::class, 'index'])->name('stitch.index');
Route::get('/stitch/{screenId}', [StitchDesignController::class, 'show'])->name('stitch.show');
Route::get('/stitch/{screenId}/screenshot', [StitchDesignController::class, 'screenshot'])->name('stitch.screenshot');
