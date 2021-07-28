<?php

namespace App\Providers;

use Domain\Assay\Models\Assay;
use Domain\Experiment\Models\Experiment;
use Domain\Study\Models\Membership;
use Domain\Study\Models\Study;
use Domain\Study\Roles\Owner;
use Domain\Study\Roles\Scientist;
use Domain\Users\Models\User;
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
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        $this->registerGates();
    }

    private function registerGates(): void
    {
        Gate::define('accept-users', function (User $user) {
            return false;
        });
        Gate::define('manage-study', function (User $user) {
            return $user->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, new Owner());
        });
        Gate::define('switch-study', function (User $user, Study $study) {
            return Membership::isMemberOfStudy($user->id, $study->id);
        });

        Gate::define('import-rdml', function (User $user) {
            return $user->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, [new Owner(), new Scientist()]);
        });

        Gate::define('edit-experiment', function (User $user, Experiment $experiment) {
            return $user->study_id === $experiment->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, [new Owner(), new Scientist()]);
        });

        Gate::define('delete-experiment', function (User $user, Experiment $experiment) {
            return $user->study_id === $experiment->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, [new Owner(), new Scientist()]);
        });
        Gate::define('download-rdml', function (User $user, Experiment $experiment) {
            return $user->study_id === $experiment->study_id;
        });
        Gate::define('download-results', function (User $user, Assay $assay) {
            return $user->study_id === $assay->study_id;
        });

        Gate::define('create-assay', function (User $user) {
            return $user->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, [new Owner(), new Scientist()]);
        });

        Gate::define('delete-assay', function (User $user, Assay $assay) {
            return $user->study_id === $assay->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, [new Owner(), new Scientist()]);
        });

        Gate::define('edit-assay', function (User $user, Assay $assay) {
            return $user->study_id === $assay->study_id
                && Membership::isRoleOfStudy($user->id, $user->study_id, [new Owner(), new Scientist()]);
        });
    }
}
