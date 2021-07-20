<?php

declare(strict_types=1);

namespace App\Admin\Studies\Controllers;

use App\Admin\Studies\Requests\StoreStudyRequest;
use Domain\Study\Actions\CreateStudyAction;
use Domain\Study\DataTransferObject\CreateStudyParameter;
use Symfony\Component\HttpFoundation\Response;

final class StoreStudyController
{
    public function __invoke(StoreStudyRequest $request, CreateStudyAction $createStudyAction): Response
    {
        $createStudyAction->execute($request->user(), new CreateStudyParameter($request->validated()));

        return redirect(route('list-experiments'));
    }
}
