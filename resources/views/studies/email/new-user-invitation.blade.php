@component('mail::message')
You have been invited by **{{$invitation->user->name}}** to join the study **{{$invitation->study->name}}**!

{{ __('You may accept this invitation by clicking the button below:') }}

@component('mail::button', ['url' => $acceptUrl])
    {{ __('Accept Invitation') }}
@endcomponent

{{ __('If you did not expect to receive an invitation to this study, you may discard this email.') }}

@endcomponent
