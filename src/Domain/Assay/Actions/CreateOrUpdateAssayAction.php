<?php

declare(strict_types=1);

namespace Domain\Assay\Actions;

use Illuminate\Support\Facades\Auth;
use Domain\Assay\Models\Assay;
use Domain\Assay\Models\AssayParameter;
use Domain\Experiment\Actions\RecalculateResultsAction;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Collection;

final class CreateOrUpdateAssayAction
{
    public function __construct(
        private RecalculateResultsAction $recalculateResultsAction,
        private Connection $connection
    ) {
    }

    public function execute(Assay $assay, Collection $parameters): Assay
    {
        $isNew = $assay->exists;
        $parameters = $parameters
            ->map(function (AssayParameter $assayParameter) {
                if (! $assayParameter->quantify) {
                    $assayParameter->slope = null;
                    $assayParameter->intercept = null;
                }

                unset($assayParameter->quantify);

                return $assayParameter;
            });

        $assay->study_id = Auth::user()->study_id;
        $assay->user_id = $assay->exists ? $assay->user_id : Auth::user()->id;
        $parametersToDelete = $assay->parameters->reject(fn (AssayParameter $parameter) => $parameters->contains($parameter));

        $this->connection->transaction(function () use ($assay, $parameters, $parametersToDelete) {
            $assay->save();
            $parametersToDelete->each->delete();
            $assay->parameters()->saveMany($parameters);
        });

        if (! $isNew && $assay->measurements->isNotEmpty()) {
            $this->recalculateResultsAction->execute($assay->measurements);
        }

        return $assay;
    }
}
