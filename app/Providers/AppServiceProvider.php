<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Assay;
use App\Models\AssayParameter;
use App\Models\DataPoint;
use App\Models\Experiment;
use App\Models\Invitation;
use App\Models\Measurement;
use App\Models\Membership;
use App\Models\QuantifyParameter;
use App\Models\Result;
use App\Models\ResultError;
use App\Models\Sample;
use App\Models\Study;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::unguard();
        Relation::enforceMorphMap([
            'user' => User::class,
            'assay' => Assay::class,
            'experiment' => Experiment::class,
            'measurement' => Measurement::class,
            'activity' => Activity::class,
            'assay_parameter' => AssayParameter::class,
            'data_point' => DataPoint::class,
            'invitation' => Invitation::class,
            'membership' => Membership::class,
            'result' => Result::class,
            'result_error' => ResultError::class,
            'sample' => Sample::class,
            'study' => Study::class,
            'quantify_parameter' => QuantifyParameter::class,
        ]);
    }
}
