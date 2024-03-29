<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo/>
        </x-slot>

        <x-validation-errors class="mb-4"/>

        <form method="POST" class="space-y-4">
            @csrf

            <div>
                <x-label for="name">{{ __('Email') }}</x-label>
                <x-input id="name" class="block mt-1 w-full" type="text" name="email" :value="$email" disabled/>
            </div>

            <div>
                <x-label for="name">{{ __('Name') }}</x-label>
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                             autofocus autocomplete="name"/>
            </div>

            <div>
                <x-label for="password">{{ __('Password') }}</x-label>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                             autocomplete="new-password"/>
            </div>

            <div>
                <x-label for="password_confirmation">{{ __('Confirm Password') }}</x-label>
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                             name="password_confirmation" required autocomplete="new-password"/>
            </div>

            <div class="flex items-center justify-end">
                <x-button>
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
