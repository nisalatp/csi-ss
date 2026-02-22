<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationalUnitController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Welcome', [
        'canLogin' => true,
    ]);
});

// Microsoft 365 Auth Routes
Route::get('/auth/signin', [AuthController::class, 'signin'])->name('auth.signin');
Route::get('/login', [AuthController::class, 'signin'])->name('login'); // Alias for middleware
Route::get('/callback', [AuthController::class, 'callback'])->name('auth.callback');
Route::post('/logout', [AuthController::class, 'signout'])->name('logout');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'users' => \App\Models\User::count(),
            'companies' => \App\Models\Company::count(),
            'ous' => \App\Models\OrganizationalUnit::count(),
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('can:access admin hub')->group(function () {
        Route::get('/admin/branding', [BrandingController::class, 'index'])->name('admin.branding.index');
        Route::get('/admin/branding/{company}/edit', [BrandingController::class, 'edit'])->name('admin.branding.edit');
        Route::patch('/admin/branding/{company}', [BrandingController::class, 'update'])->name('admin.branding.update');
        
        // OU Management
        Route::get('/admin/ou', [OrganizationalUnitController::class, 'index'])->name('admin.ou.index');
        Route::post('/admin/ou', [OrganizationalUnitController::class, 'store'])->name('admin.ou.store');
        Route::patch('/admin/ou/{organizationalUnit}', [OrganizationalUnitController::class, 'update'])->name('admin.ou.update');
        Route::delete('/admin/ou/{organizationalUnit}', [OrganizationalUnitController::class, 'destroy'])->name('admin.ou.destroy');

        // OU Type Management
        Route::post('/admin/ou-types', [OrganizationalUnitController::class, 'storeType'])->name('admin.ou-types.store');
        Route::patch('/admin/ou-types/{ouType}', [OrganizationalUnitController::class, 'updateType'])->name('admin.ou-types.update');
        Route::delete('/admin/ou-types/{ouType}', [OrganizationalUnitController::class, 'destroyType'])->name('admin.ou-types.destroy');

        // User Management
        Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/admin/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
    });
});

require __DIR__.'/auth.php';
