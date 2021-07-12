<?php

declare(strict_types=1);

namespace App\Admin\Studies\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class SwitchStudyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'study_id' => 'required|integer',
        ];
    }
}
