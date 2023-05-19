<?php

use App\Http\Controllers\AcceptInvitationAsExistingUserController;
use App\Http\Controllers\AcceptInvitationAsNewUserController;
use App\Http\Controllers\CreateAssayController;
use App\Http\Controllers\CreateFirstStudyController;
use App\Http\Controllers\CreateStudyController;
use App\Http\Controllers\DownloadAssayController;
use App\Http\Controllers\DownloadAssayReportsController;
use App\Http\Controllers\DownloadRdmlController;
use App\Http\Controllers\EditAssayController;
use App\Http\Controllers\EditExperimentController;
use App\Http\Controllers\ExportLogController;
use App\Http\Controllers\ExportResultsController;
use App\Http\Controllers\ListAssaysController;
use App\Http\Controllers\ListExperimentsController;
use App\Http\Controllers\ListLogController;
use App\Http\Controllers\ListResultsController;
use App\Http\Controllers\RegisterWithInvitationController;
use App\Http\Controllers\ShowSampleController;
use App\Http\Controllers\ShowSampleReportController;
use App\Http\Controllers\ShowSampleReportPdfController;
use App\Http\Controllers\ShowStudySettingsController;
use App\Http\Controllers\StoreStudyController;
use App\Http\Controllers\SwitchStudyController;
use App\Http\Controllers\UpdateExperimentController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureHasStudy;

Route::view('/', 'welcome');
Route::view('documentation', 'api-documentation')->name('api-documentation');
Route::get('/invitation/{invitation}/accepts/register', AcceptInvitationAsNewUserController::class)
    ->middleware('signed')
    ->name('invitations.accept.new');
Route::post('/invitation/{invitation}/accepts/register', RegisterWithInvitationController::class)->middleware('signed');

Route::get('/invitation/{invitation}/accepts/login', AcceptInvitationAsExistingUserController::class)
    ->middleware('signed')
    ->name('invitations.accept.existing');

Route::get('/assays/{assay}/samples/{sample}/report', ShowSampleReportController::class)
    //->middleware('signed')
    ->name('samples.report');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('studies/create-first', CreateFirstStudyController::class)->name('studies.create-first');
    Route::post('studies', StoreStudyController::class)->name('studies.store');
    Route::middleware(EnsureHasStudy::class)->group(function () {
        Route::redirect('dashboard', 'experiments');

        Route::get('assays', ListAssaysController::class)->name('assays.index');
        Route::get('assays/create', CreateAssayController::class)->middleware('can:create-assay');
        Route::get('assays/{assay}', EditAssayController::class)->name('assays.edit')->middleware('can:edit-assay,assay');
        Route::get('assays/{assay}/download', DownloadAssayController::class)->name('assays.download');

        Route::get('studies/create', CreateStudyController::class)->name('studies.create');
        Route::get('current-study/settings', ShowStudySettingsController::class)
            ->name('currentStudy.show')
            ->middleware('can:manage-study');
        Route::put('current-study/{study}', SwitchStudyController::class)->name('currentStudy.switch')->middleware('can:switch-study,study');

        Route::get('results', ListResultsController::class)->name('results.index');
        Route::get('results/{assay}/export', ExportResultsController::class)
            ->name('results.export')
            ->middleware('can:download-results,assay');

        Route::get('samples/{sample}', ShowSampleController::class)->name('samples.show');
        Route::get('/assays/{assay}/samples/{sample}/report/pdf', ShowSampleReportPdfController::class)->name('samples.report.pdf');
        Route::get('/assays/{assay}/reports', DownloadAssayReportsController::class)->name('assays.reports');

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
