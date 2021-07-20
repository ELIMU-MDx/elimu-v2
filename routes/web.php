<?php

use App\Admin\Assays\Controllers\CreateAssayController;
use App\Admin\Assays\Controllers\EditAssayController;
use App\Admin\Assays\Controllers\ListAssaysController;
use App\Admin\Studies\Controllers\AcceptInvitationController;
use App\Admin\Studies\Controllers\CreateFirstStudyController;
use App\Admin\Studies\Controllers\CreateStudyController;
use App\Admin\Studies\Controllers\RegisterWithInvitationController;
use App\Admin\Studies\Controllers\ShowStudySettingsController;
use App\Admin\Studies\Controllers\StoreStudyController;
use App\Admin\Studies\Controllers\SwitchStudyController;
use Illuminate\Support\Facades\Route;
use Support\Middlewares\EnsureHasStudy;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/invitation/{invitation}/accepts',
    AcceptInvitationController::class)->middleware('signed')->name('accept-invitation');
Route::post('/invitation/{invitation}/accepts', RegisterWithInvitationController::class)->middleware('signed');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::middleware(EnsureHasStudy::class)->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');

        Route::get('assays', ListAssaysController::class)->name('list-assays');
        Route::get('assays/create', CreateAssayController::class);
        Route::get('assays/{assay}', EditAssayController::class)->name('edit-assay');
    });
    Route::get('studies/create-first', CreateFirstStudyController::class)->name('create-first-study');
    Route::get('studies/create', CreateStudyController::class)->name('create-study');
    Route::post('studies', StoreStudyController::class)->name('store-study');
    Route::get('current-study/settings', ShowStudySettingsController::class)->name('show-study');
    Route::put('current-study', SwitchStudyController::class)->name('switch-study');

    Route::get('experiments',
        \App\Admin\Experiments\Controllers\ListExperimentsController::class)->name('list-experiments');
});
