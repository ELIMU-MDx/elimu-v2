<?php

use App\Admin\Assays\Controllers\CreateAssayController;
use App\Admin\Assays\Controllers\EditAssayController;
use App\Admin\Assays\Controllers\ListAssaysController;
use App\Admin\Experiments\Controllers\DownloadRdmlController;
use App\Admin\Experiments\Controllers\EditExperimentController;
use App\Admin\Experiments\Controllers\ExportResultsController;
use App\Admin\Experiments\Controllers\ListExperimentsController;
use App\Admin\Experiments\Controllers\ListResultsController;
use App\Admin\Experiments\Controllers\ShowSampleController;
use App\Admin\Experiments\Controllers\UpdateExperimentController;
use App\Admin\QualityControl\Controllers\ExportLogController;
use App\Admin\QualityControl\Controllers\ListLogController;
use App\Admin\Studies\Controllers\AcceptInvitationAsExistingUserController;
use App\Admin\Studies\Controllers\AcceptInvitationAsNewUserController;
use App\Admin\Studies\Controllers\CreateFirstStudyController;
use App\Admin\Studies\Controllers\CreateStudyController;
use App\Admin\Studies\Controllers\RegisterWithInvitationController;
use App\Admin\Studies\Controllers\ShowStudySettingsController;
use App\Admin\Studies\Controllers\StoreStudyController;
use App\Admin\Studies\Controllers\SwitchStudyController;
use Illuminate\Support\Facades\Route;
use Support\Middlewares\EnsureHasStudy;

Route::view('/', 'welcome');
Route::view('documentation', 'api-documentation')->name('api-documentation');
Route::get('/invitation/{invitation}/accepts/register', AcceptInvitationAsNewUserController::class)
    ->middleware('signed')
    ->name('invitations.accept.new');
Route::post('/invitation/{invitation}/accepts/register', RegisterWithInvitationController::class)->middleware('signed');

Route::get('/invitation/{invitation}/accepts/login', AcceptInvitationAsExistingUserController::class)
    ->middleware('signed')
    ->name('invitations.accept.existing');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('studies/create-first', CreateFirstStudyController::class)->name('studies.create-first');
    Route::post('studies', StoreStudyController::class)->name('studies.store');
    Route::middleware(EnsureHasStudy::class)->group(function () {
        Route::redirect('dashboard', 'experiments');

        Route::get('assays', ListAssaysController::class)->name('assays.index');
        Route::get('assays/create', CreateAssayController::class)->middleware('can:create-assay');
        Route::get('assays/{assay}', EditAssayController::class)->name('assays.edit')->middleware('can:edit-assay,assay');

        Route::get('studies/create', CreateStudyController::class)->name('studies.create');
        Route::get('current-study/settings', ShowStudySettingsController::class)
            ->name('currentStudy.show')
            ->middleware('can:manage-study');
        Route::put('current-study/{study}', SwitchStudyController::class)->name('currentStudy.switch')->middleware('can:switch-study,study');

        Route::get('results', ListResultsController::class)->name('results.index');
        Route::get('results/{assay}/export', ExportResultsController::class)
            ->name('results.export')
            ->middleware('can:download-results,assay');

        Route::get('samples/{sample}', ShowSampleController::class);

        Route::get('experiments', ListExperimentsController::class)->name('experiments.index');
        Route::get('experiments/{experiment}/rdml', DownloadRdmlController::class)
            ->name('experiments.download')
            ->middleware('can:download-rdml,experiment');

        Route::get(
            'experiments/{experiment}/edit',
            EditExperimentController::class
        )->name('experiments.edit')->middleware('can:edit-experiment,experiment');
        Route::put(
            'experiments/{experiment}',
            UpdateExperimentController::class
        )->name('experiments.update')->middleware('can:edit-experiment,experiment');

        Route::get('quality-control', ListLogController::class)->name('quality-control.index');
        Route::get('quality-control/export', ExportLogController::class)->name('quality-control.export');
    });
});
