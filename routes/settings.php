<?php

use App\Http\Controllers\PermissionExportController;
use App\Http\Controllers\RoleExportController;
use App\Livewire\Permissions\Form;
use App\Livewire\Permissions\Index;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::livewire('settings/profile', Profile::class)->name('profile.edit');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('settings/password', Password::class)->name('user-password.edit');
    Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::livewire('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    Route::get('/roles', RoleIndex::class)
        ->name('roles.index')
        ->middleware('permission:view roles');

    Route::get('/roles/create', RoleForm::class)
        ->name('roles.create')
        ->middleware('permission:create roles');

    Route::get('/roles/{role}/edit', RoleForm::class)
        ->name('roles.edit')
        ->middleware('permission:edit roles');

    Route::get('/roles/export/csv', [RoleExportController::class, 'csv'])
        ->name('roles.export.csv')
        ->middleware('permission:export roles');

    Route::get('/roles/export/xlsx', [RoleExportController::class, 'xlsx'])
        ->name('roles.export.xlsx')
        ->middleware('permission:export roles');

    Route::get('/roles/print', [RoleExportController::class, 'print'])
        ->name('roles.print')
        ->middleware('permission:print roles');

    Route::get('/roles/pdf', [RoleExportController::class, 'pdf'])
        ->name('roles.pdf')
        ->middleware('permission:print roles');

    Route::get('/permissions', Index::class)
        ->name('permissions.index')
        ->middleware('permission:view permissions');

    Route::get('/permissions/create', Form::class)
        ->name('permissions.create')
        ->middleware('permission:create permissions');

    Route::get('/permissions/{permissionModel}/edit', Form::class)
        ->name('permissions.edit')
        ->middleware('permission:edit permissions');

    Route::get('/permissions/export/csv', [PermissionExportController::class, 'csv'])
        ->name('permissions.export.csv')
        ->middleware('permission:export permissions');

    Route::get('/permissions/export/xlsx', [PermissionExportController::class, 'xlsx'])
        ->name('permissions.export.xlsx')
        ->middleware('permission:export permissions');

    Route::get('/permissions/print', [PermissionExportController::class, 'print'])
        ->name('permissions.print')
        ->middleware('permission:print permissions');

    Route::get('/permissions/pdf', [PermissionExportController::class, 'pdf'])
        ->name('permissions.pdf')
        ->middleware('permission:print permissions');
});
