<?php

declare(strict_types=1);

namespace App\Admin\Experiments\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateExperimentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'eln' => 'string|max:255',
        ];
    }
}
