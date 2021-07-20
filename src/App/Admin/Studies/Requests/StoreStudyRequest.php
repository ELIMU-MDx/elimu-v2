<?php

declare(strict_types=1);

namespace App\Admin\Studies\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreStudyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'identifier' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ];
    }

}
