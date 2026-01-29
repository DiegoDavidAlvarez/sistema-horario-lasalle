<?php

use App\Http\Controllers\Admin\DocenteController;
use App\Http\Controllers\Admin\ModuloController;
use App\Http\Controllers\Admin\PlanEstudioController;
use App\Http\Controllers\Admin\ProgramaEstudioController;
use App\Http\Controllers\Admin\UnidadDidacticaController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::resource('docente', DocenteController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.docente');
    Route::get('docente/consultar-dni', [DocenteController::class, 'consultarDni'])
        ->name('admin.docente.consultar-dni');
    Route::put('docente/{id}/restore', [DocenteController::class, 'restore'])
        ->name('admin.docente.restore');

    Route::resource('programa-estudio', ProgramaEstudioController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.programa-estudio');
    Route::put('programa-estudio/{id}/restore', [ProgramaEstudioController::class, 'restore'])
        ->name('admin.programa-estudio.restore');

    Route::resource('modulos', ModuloController::class)
        ->only(['store', 'update', 'destroy'])
        ->names('admin.modulos');

    Route::resource('unidad-didactica', UnidadDidacticaController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.unidad-didactica');
    Route::put('unidad-didactica/{id}/restore', [UnidadDidacticaController::class, 'restore'])
        ->name('admin.unidad-didactica.restore');

    Route::resource('plan-estudio', PlanEstudioController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.plan-estudio');
    Route::put('plan-estudio/{id}/restore', [PlanEstudioController::class, 'restore'])
        ->name('admin.plan-estudio.restore');
});
