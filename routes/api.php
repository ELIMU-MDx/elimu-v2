<?php

use App\Http\Controllers\Api\ListResultController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/assays/{assay}/results', ListResultController::class)->name('api.results.index');
});
