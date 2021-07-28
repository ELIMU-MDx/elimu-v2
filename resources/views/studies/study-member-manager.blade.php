<div>
    <div class="mt-10 sm:mt-0" x-data="{role: @entangle('addMemberForm.role').defer }">
        <x-jet-form-section submit="addMember">
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
                    <x-jet-label for="email" value="{{ __('Email') }}"/>
                    <x-jet-input id="email" type="email" class="mt-1 block w-full"
                                 wire:model.defer="addMemberForm.email"/>
                    <x-jet-input-error for="addMemberForm.email" class="mt-2"/>
                </div>

                <!-- Role -->
                <div class="col-span-6 lg:col-span-4">
                    <x-jet-label for="role" value="{{ __('Role') }}"/>
                    <x-jet-input-error for="addMemberForm.role" class="mt-2"/>

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
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Added.') }}
                </x-jet-action-message>
                <x-jet-button>
                    {{ __('Add') }}
                </x-jet-button>
            </x-slot>
        </x-jet-form-section>
    </div>

    @if($study->invitations->isNotEmpty())
        <x-jet-section-border/>
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
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
                        @foreach($study->invitations as $invitation)
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
            </x-jet-action-section>
        </div>
    @endif
    @if ($study->users->isNotEmpty())
        <x-jet-section-border/>

        <!-- Manage Team Members -->
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
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
                                    <div class="ml-4">{{ $user->name }}</div>
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
            </x-jet-action-section>
        </div>
@endif

<!-- Remove Team Member Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingMemberRemoval">
        <x-slot name="title">
            {{ __('Remove Team Member') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this person from the team?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingMemberRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="removeMember" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
