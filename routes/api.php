<?php

use App\Api\Results\Controllers\ListResultController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/assay/{assay}/results', ListResultController::class)->name('api.results.index');
});
