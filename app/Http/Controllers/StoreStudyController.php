<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudyRequest;
use Domain\Study\Actions\CreateStudyAction;
use Domain\Study\DataTransferObject\CreateStudyParameter;
use Symfony\Component\HttpFoundation\Response;

final class StoreStudyController
{
    public function __invoke(StoreStudyRequest $request, CreateStudyAction $createStudyAction): Response
    {
        $createStudyAction->execute($request->user(), CreateStudyParameter::from($request->validated()));

        return redirect(route('currentStudy.show'));
    }
}
