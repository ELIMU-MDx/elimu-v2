<div>
    <div class="mt-10 sm:mt-0" x-data="{role: @entangle('addMemberForm.role') }">
        <x-form-section submit="addMember">
            <x-slot name="title">
                {{ __('Add Team Member') }}
            </x-slot>

            <x-slot name="description">
                Add a new team member to your study, allowing them to collaborate with you.
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <div class="max-w-xl text-sm text-gray-600">
                        Please provide the email address of the person you would like to add to this team.
                    </div>
                </div>

                <!-- Member Email -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="email">{{ __('Email') }}</x-label>
                    <x-input name="email" id="email" type="email" class="mt-1 block w-full"
                                 wire:model="addMemberForm.email"/>
                    <x-input-error for="addMemberForm.email" class="mt-2"/>
                </div>

                <!-- Role -->
                <div class="col-span-6 lg:col-span-4">
                    <x-label for="role">{{ __('Role') }}</x-label>
                    <x-input-error for="addMemberForm.role" class="mt-2"/>

                    <div class="mt-1">
                        @foreach($roles as $key => $role)
                            <x-radio-button-group name="role" id="role-{{$key}}" x-model="role"
                                                  value="{{$role['identifier']}}">
                                <x-slot name="title">{{$role['title']}}</x-slot>
                                <x-slot name="description">{{$role['description']}}</x-slot>
                            </x-radio-button-group>
                        @endforeach
                    </div>
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-action-message class="mr-3" on="saved">
                    {{ __('Added.') }}
                </x-action-message>
                <x-button>
                    {{ __('Add') }}
                </x-button>
            </x-slot>
        </x-form-section>
    </div>

    @if($study->pendingInvitations->isNotEmpty())
        <x-section-border/>
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Pending Team Invitations') }}
                </x-slot>

                <x-slot name="description">
                    These people have been invited to your team and have been sent an invitation email. They may join
                    the
                    team
                    by accepting the email invitation.
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach($study->pendingInvitations as $invitation)
                            <div class="flex items-center justify-between">
                                <div class="text-gray-600">{{$invitation->email}}</div>

                                <div class="flex items-center">
                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                            type="button"
                                            wire:click="cancelTeamInvitation('{{ $invitation->id }}')">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif
    @if ($study->users->isNotEmpty())
        <x-section-border/>

        <!-- Manage Team Members -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Team Members') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('All of the people that are part of the team.') }}
                </x-slot>

                <!-- Team Member List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($study->users->sortBy('name') as $user)
                            <div class="flex items-center justify-between" wire:key="user-{{$user->id}}">
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}"
                                         alt="{{ $user->name }}">
                                    <div class="ml-4">{{ $user->name }} <span class="text-gray-400">{{$user->email}}</span></div>
                                </div>

                                <div class="flex items-center">
                                    <!-- Manage Team Member Role -->
                                    {{--  <button class="ml-2 text-sm text-gray-400 underline" wire:click="manageRole('{{ $user->id }}')">
                                          {{ Laravel\Jetstream\Jetstream::findRole($user->membership->role)->name }}
                                      </button>--}}
                                    <div class="ml-2 text-sm text-gray-400">
                                        {{$user->membership->role->title()}}
                                    </div>

                                    <!-- Leave Team -->
                                    {{--  @if ($this->user->id === $user->id)
                                          <button class="cursor-pointer ml-6 text-sm text-red-500" wire:click="$toggle('confirmingLeavingTeam')">
                                              {{ __('Leave') }}
                                          </button>--}}

                                    <button class="cursor-pointer ml-6 text-sm text-red-500"
                                            type="button"
                                            wire:click="confirmingMemberRemoval('{{ $user->id }}')">
                                        {{ __('Remove') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
@endif

<!-- Remove Team Member Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingMemberRemoval">
        <x-slot name="title">
            {{ __('Remove Team Member') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this person from the team?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingMemberRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ml-2" wire:click="removeMember" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
