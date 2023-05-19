<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Fortify\Rules\Password;

final class LoginWithInvitationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', new Password()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->route('invitation')->email,
        ]);
    }
}
