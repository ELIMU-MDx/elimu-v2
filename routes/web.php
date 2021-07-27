<?php

use App\Admin\Assays\Controllers\CreateAssayController;
use App\Admin\Assays\Controllers\EditAssayController;
use App\Admin\Assays\Controllers\ListAssaysController;
use App\Admin\Experiments\Controllers\DownloadRdmlController;
use App\Admin\Experiments\Controllers\EditExperimentController;
use App\Admin\Experiments\Controllers\ExportResultsController;
use App\Admin\Experiments\Controllers\ListExperimentsController;
use App\Admin\Experiments\Controllers\ListResultsController;
use App\Admin\Experiments\Controllers\UpdateExperimentController;
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

Route::get(
    '/invitation/{invitation}/accepts',
    AcceptInvitationController::class
)->middleware('signed')->name('accept-invitation');
Route::post('/invitation/{invitation}/accepts', RegisterWithInvitationController::class)->middleware('signed');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('studies/create-first', CreateFirstStudyController::class)->name('studies.create-first');
    Route::post('studies', StoreStudyController::class)->name('studies.store');
    Route::middleware(EnsureHasStudy::class)->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');

        Route::get('assays', ListAssaysController::class)->name('assays.index');
        Route::get('assays/create', CreateAssayController::class);
        Route::get('assays/{assay}', EditAssayController::class)->name('assays.edit');

        Route::get('studies/create', CreateStudyController::class)->name('studies.create');
        Route::get('current-study/settings', ShowStudySettingsController::class)->name('currentStudy.show');
        Route::put('current-study', SwitchStudyController::class)->name('currentStudy.switch');

        Route::get('results', ListResultsController::class)->name('results.index');
        Route::get('results/{assay}/export', ExportResultsController::class)->name('results.export');

        Route::get('experiments', ListExperimentsController::class)->name('experiments.index');
        Route::get('experiments/{experiment}/rdml', DownloadRdmlController::class)->name('experiments.download');

        Route::get('experiments/{experiment}/edit', EditExperimentController::class)->name('experiments.edit');
        Route::put('experiments/{experiment}', UpdateExperimentController::class)->name('experiments.update');

    });
});
