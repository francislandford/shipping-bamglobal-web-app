<?php

use App\Http\Controllers\AgencyExportController;
use App\Http\Controllers\CargoExportController;
use App\Http\Controllers\CargoTallyEntryExportController;
use App\Http\Controllers\PierExportController;
use App\Http\Controllers\PortExportController;
use App\Http\Controllers\ShipExportController;
use App\Http\Controllers\StatementOfFactExportController;
use App\Http\Controllers\UserExportController;
use App\Livewire\Agency\AgencyForm;
use App\Livewire\Agency\AgencyIndex;
use App\Livewire\Cargo\CargoForm;
use App\Livewire\Cargo\CargoIndex;
use App\Livewire\CargoTallyEntries\CargoTallyEntryForm;
use App\Livewire\CargoTallyEntries\CargoTallyEntryIndex;
use App\Livewire\CargoTallyEntries\CargoTallyEntryShow;
use App\Livewire\Dashboard;
use App\Livewire\Piers\PierForm;
use App\Livewire\Piers\PierIndex;
use App\Livewire\Port\PortForm;
use App\Livewire\Port\PortIndex;
use App\Livewire\Ships\Index;
use App\Livewire\Ships\ShipForm;
use App\Livewire\StatementOfFacts\StatementOfFactsForm;
use App\Livewire\StatementOfFacts\StatementOfFactsIndex;
use App\Livewire\StatementOfFacts\StatementOfFactsShow;
use App\Livewire\Users\Form;
use App\Livewire\Users\UserIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
    Route::get('/users', UserIndex::class)
        ->name('users.index')
        ->middleware('permission:view users');

    Route::get('/users/create', Form::class)
        ->name('users.create')
        ->middleware('permission:create users');

    Route::get('/users/{user}/edit', Form::class)
        ->name('users.edit')
        ->middleware('permission:edit users');

    Route::get('/users/export/csv', [UserExportController::class, 'csv'])
        ->name('users.export.csv')
        ->middleware('permission:export users');

    Route::get('/users/export/xlsx', [UserExportController::class, 'xlsx'])
        ->name('users.export.xlsx')
        ->middleware('permission:export users');

    Route::get('/users/print', [UserExportController::class, 'print'])
        ->name('users.print')
        ->middleware('permission:print users');

    Route::get('/users/pdf', [UserExportController::class, 'pdf'])
        ->name('users.pdf')
        ->middleware('permission:print users');

    Route::get('/ships', Index::class)
        ->name('ships.index')
        ->middleware('permission:view ships');

    Route::get('/ships/create', ShipForm::class)
        ->name('ships.create')
        ->middleware('permission:create ships');

    Route::get('/ships/{ship}/edit', ShipForm::class)
        ->name('ships.edit')
        ->middleware('permission:edit ships');

    Route::get('/ships/export/csv', [ShipExportController::class, 'csv'])
        ->name('ships.export.csv')
        ->middleware('permission:export ships');

    Route::get('/ships/export/xlsx', [ShipExportController::class, 'xlsx'])
        ->name('ships.export.xlsx')
        ->middleware('permission:export ships');

    Route::get('/ships/print', [ShipExportController::class, 'print'])
        ->name('ships.print')
        ->middleware('permission:print ships');

    Route::get('/ships/pdf', [ShipExportController::class, 'pdf'])
        ->name('ships.pdf')
        ->middleware('permission:print ships');

    Route::get('/agencies', AgencyIndex::class)
        ->name('agencies.index')
        ->middleware('permission:view agencies');

    Route::get('/agencies/create', AgencyForm::class)
        ->name('agencies.create')
        ->middleware('permission:create agencies');

    Route::get('/agencies/{agency}/edit', AgencyForm::class)
        ->name('agencies.edit')
        ->middleware('permission:edit agencies');

    Route::get('/agencies/export/csv', [AgencyExportController::class, 'csv'])
        ->name('agencies.export.csv')
        ->middleware('permission:export agencies');

    Route::get('/agencies/export/xlsx', [AgencyExportController::class, 'xlsx'])
        ->name('agencies.export.xlsx')
        ->middleware('permission:export agencies');

    Route::get('/agencies/print', [AgencyExportController::class, 'print'])
        ->name('agencies.print')
        ->middleware('permission:print agencies');

    Route::get('/agencies/pdf', [AgencyExportController::class, 'pdf'])
        ->name('agencies.pdf')
        ->middleware('permission:print agencies');

    Route::get('/ports', PortIndex::class)
        ->name('ports.index')
        ->middleware('permission:view ports');

    Route::get('/ports/create', PortForm::class)
        ->name('ports.create')
        ->middleware('permission:create ports');

    Route::get('/ports/{port}/edit', PortForm::class)
        ->name('ports.edit')
        ->middleware('permission:edit ports');

    Route::get('/ports/export/csv', [PortExportController::class, 'csv'])
        ->name('ports.export.csv')
        ->middleware('permission:export ports');

    Route::get('/ports/export/xlsx', [PortExportController::class, 'xlsx'])
        ->name('ports.export.xlsx')
        ->middleware('permission:export ports');

    Route::get('/ports/print', [PortExportController::class, 'print'])
        ->name('ports.print')
        ->middleware('permission:print ports');

    Route::get('/ports/pdf', [PortExportController::class, 'pdf'])
        ->name('ports.pdf')
        ->middleware('permission:print ports');
    Route::get('/piers', PierIndex::class)
        ->name('piers.index')
        ->middleware('permission:view piers');

    Route::get('/piers/create', PierForm::class)
        ->name('piers.create')
        ->middleware('permission:create piers');

    Route::get('/piers/{pier}/edit', PierForm::class)
        ->name('piers.edit')
        ->middleware('permission:edit piers');

    Route::get('/piers/export/csv', [PierExportController::class, 'csv'])
        ->name('piers.export.csv')
        ->middleware('permission:export piers');

    Route::get('/piers/export/xlsx', [PierExportController::class, 'xlsx'])
        ->name('piers.export.xlsx')
        ->middleware('permission:export piers');

    Route::get('/piers/print', [PierExportController::class, 'print'])
        ->name('piers.print')
        ->middleware('permission:print piers');

    Route::get('/piers/pdf', [PierExportController::class, 'pdf'])
        ->name('piers.pdf')
        ->middleware('permission:print piers');

    Route::get('/cargo-tally-entries', CargoTallyEntryIndex::class)
        ->name('cargo-tally-entries.index')
        ->middleware('permission:view cargo tally entries');

    Route::get('/cargo-tally-entries/create', CargoTallyEntryForm::class)
        ->name('cargo-tally-entries.create')
        ->middleware('permission:create cargo tally entries');

    Route::get('/cargo-tally-entries/export/csv', [CargoTallyEntryExportController::class, 'csv'])
        ->name('cargo-tally-entries.export.csv')
        ->middleware('permission:export cargo tally entries');

    Route::get('/cargo-tally-entries/export/xlsx', [CargoTallyEntryExportController::class, 'xlsx'])
        ->name('cargo-tally-entries.export.xlsx')
        ->middleware('permission:export cargo tally entries');

    Route::get('/cargo-tally-entries/print', [CargoTallyEntryExportController::class, 'print'])
        ->name('cargo-tally-entries.print')
        ->middleware('permission:print cargo tally entries');

    Route::get('/cargo-tally-entries/pdf', [CargoTallyEntryExportController::class, 'pdf'])
        ->name('cargo-tally-entries.pdf')
        ->middleware('permission:print cargo tally entries');

    Route::get('/cargo-tally-entries/{cargoTallyEntry}/print', [CargoTallyEntryExportController::class, 'printSingle'])
        ->whereNumber('cargoTallyEntry')
        ->name('cargo-tally-entries.print-single')
        ->middleware('permission:print cargo tally entries');

    Route::get('/cargo-tally-entries/{cargoTallyEntry}/pdf', [CargoTallyEntryExportController::class, 'pdfSingle'])
        ->whereNumber('cargoTallyEntry')
        ->name('cargo-tally-entries.pdf-single')
        ->middleware('permission:print cargo tally entries');

    Route::get('/cargo-tally-entries/{cargoTallyEntry}/edit', CargoTallyEntryForm::class)
        ->whereNumber('cargoTallyEntry')
        ->name('cargo-tally-entries.edit')
        ->middleware('permission:edit cargo tally entries');

    Route::get('/cargo-tally-entries/{cargoTallyEntry}', CargoTallyEntryShow::class)
        ->whereNumber('cargoTallyEntry')
        ->name('cargo-tally-entries.show')
        ->middleware('permission:view cargo tally entries');

    Route::get('/statement-of-facts', StatementOfFactsIndex::class)
        ->name('statement-of-facts.index')
        ->middleware('permission:view statement of facts');

    Route::get('/statement-of-facts/create', StatementOfFactsForm::class)
        ->name('statement-of-facts.create')
        ->middleware('permission:create statement of facts');

    Route::get('/statement-of-facts/export/csv', [StatementOfFactExportController::class, 'csv'])
        ->name('statement-of-facts.export.csv')
        ->middleware('permission:export statement of facts');

    Route::get('/statement-of-facts/export/xlsx', [StatementOfFactExportController::class, 'xlsx'])
        ->name('statement-of-facts.export.xlsx')
        ->middleware('permission:export statement of facts');

    Route::get('/statement-of-facts/print', [StatementOfFactExportController::class, 'print'])
        ->name('statement-of-facts.print')
        ->middleware('permission:print statement of facts');

    Route::get('/statement-of-facts/{statementOfFact}/print', [StatementOfFactExportController::class, 'printSingle'])
        ->whereNumber('statementOfFact')
        ->name('statement-of-facts.print-single')
        ->middleware('permission:print statement of facts');

    Route::get('/statement-of-facts/pdf', [StatementOfFactExportController::class, 'pdf'])
        ->name('statement-of-facts.pdf')
        ->middleware('permission:print statement of facts');

    Route::get('/statement-of-facts/pdf-single', [StatementOfFactExportController::class, 'pdfSingle'])
        ->name('statement-of-facts.pdf-single')
        ->middleware('permission:print statement of facts');

    Route::get('/statement-of-facts/{statementOfFact}/edit', StatementOfFactsForm::class)
        ->name('statement-of-facts.edit')
        ->middleware('permission:edit statement of facts');

    Route::get('/statement-of-facts/{statementOfFact}', StatementOfFactsShow::class)
        ->name('statement-of-facts.show')
        ->middleware('permission:view statement of facts');


    Route::get('/cargos', CargoIndex::class)
        ->name('cargos.index')
        ->middleware('permission:view cargos');

    Route::get('/cargos/create', CargoForm::class)
        ->name('cargos.create')
        ->middleware('permission:create cargos');

    Route::get('/cargos/export/csv', [CargoExportController::class, 'csv'])
        ->name('cargos.export.csv')
        ->middleware('permission:export cargos');

    Route::get('/cargos/export/xlsx', [CargoExportController::class, 'xlsx'])
        ->name('cargos.export.xlsx')
        ->middleware('permission:export cargos');

    Route::get('/cargos/print', [CargoExportController::class, 'print'])
        ->name('cargos.print')
        ->middleware('permission:print cargos');

    Route::get('/cargos/pdf', [CargoExportController::class, 'pdf'])
        ->name('cargos.pdf')
        ->middleware('permission:print cargos');

    Route::get('/cargos/{cargo}/edit', CargoForm::class)
        ->whereNumber('cargo')
        ->name('cargos.edit')
        ->middleware('permission:edit cargos');


});

require __DIR__.'/settings.php';
