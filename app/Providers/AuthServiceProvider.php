<?php

namespace App\Providers;

use App\Models\Assay;
use App\Models\Experiment;
use App\Models\Sample;
use App\Models\Study;
use App\Models\User;
use Domain\Experiment\DataTransferObjects\ExperimentListItem;
use Domain\Study\Roles\Owner;
use Domain\Study\Roles\Scientist;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerGates();
    }

    private function registerGates(): void
    {
        Gate::define('accept-users', fn (User $user) => false);
        Gate::define('manage-study', fn (User $user) => $user->study_id && $user->studies->first(fn (Study $study) => $study->membership->role->identifier() === (new Owner())->identifier()));
        Gate::define('switch-study', fn (User $user, Study $study) => $user->studies->firstWhere('id', $study->id));

        Gate::define('import-rdml', fn (User $user) => $user->study_id && $user->studies->first(fn (Study $study) => in_array($study->membership->role, [new Owner(), new Scientist()], false)));

        Gate::define('edit-experiment', function (User $user, Experiment|ExperimentListItem $experiment) {
            $studyId = $experiment instanceof Experiment ? $experiment->study_id : $experiment->studyId;

            return $user->study_id === $studyId && in_array($user->studies->firstWhere('id', $studyId)->membership->role, [new Owner(), new Scientist()], false);
        });

        Gate::define('delete-experiment', fn (User $user, ExperimentListItem $experiment) => $user->study_id === $experiment->studyId && in_array($user->studies->firstWhere('id', $experiment->studyId)->membership->role, [new Owner(), new Scientist()], false));
        Gate::define('download-rdml', fn (User $user, Experiment $experiment) => $user->study_id === $experiment->study_id);
        Gate::define('download-results', fn (User $user, Assay $assay) => $user->study_id === $assay->study_id);

        Gate::define('create-assay', fn (User $user) => $user->study_id && $user->studies->first(fn (Study $study) => in_array($study->membership->role, [new Owner(), new Scientist()], false)));

        Gate::define('delete-assay', fn (User $user, Assay $assay) => $user->study_id === $assay->study_id && in_array($user->studies->firstWhere('id', $assay->study_id)->membership->role, [new Owner(), new Scientist()], false));

        Gate::define('edit-assay', fn (User $user, Assay $assay) => $user->study_id === $assay->study_id && in_array($user->studies->firstWhere('id', $assay->study_id)->membership->role, [new Owner(), new Scientist()], false));

        Gate::define('edit-results', fn (User $user, Sample $sample) => $user->study_id === $sample->study_id && in_array($user->studies->firstWhere('id', $sample->study_id)->membership->role, [new Owner(), new Scientist()], false));
    }
}
