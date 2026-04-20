<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CargoTallyPermissionController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\StatementOfFactPermissionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/statement-of-facts/permissions', StatementOfFactPermissionController::class);
    Route::get('/cargo-tally/permissions', CargoTallyPermissionController::class);
    Route::get('/master-data', [MasterDataController::class, 'index']);

    Route::post('/cargo-tally-entries', [\App\Http\Controllers\Api\CargoTallyEntryController::class, 'store']);
    Route::post('/statement-of-facts', [\App\Http\Controllers\Api\StatementOfFactController::class, 'store']);

    Route::get('/cargo-tally-entries', [\App\Http\Controllers\Api\CargoTallyEntryController::class, 'index']);
    Route::get('/statement-of-facts', [\App\Http\Controllers\Api\StatementOfFactController::class, 'index']);

    Route::get('/cargo-tally-entries/{cargoTallyEntry}', [\App\Http\Controllers\Api\CargoTallyEntryController::class, 'show']);

    Route::get('/statement-of-facts/{statementOfFact}', [\App\Http\Controllers\Api\StatementOfFactController::class, 'show']);
});
