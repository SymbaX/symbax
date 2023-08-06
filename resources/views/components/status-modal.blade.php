@if (session('status'))
    <x-modal name="status-modal" :show="true" focusable>
        <div class="mt-3 text-center sm:mt-5">
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    @switch(session('status'))
                        @case('join-request-event')
                            {{ __('Your request to join the event has been sent.') }}
                        @break

                        @case('your-event-owner')
                            {{ __('I cant join an event I created') }}
                        @break

                        @case('no-participation-slots')
                            {{ __('There are no participation slots.') }}
                        @break

                        @case('already-joined')
                            {{ __('I have already attended the event.') }}
                        @break

                        @case('not-joined')
                            {{ __('I have not attended the event.') }}
                        @break

                        @case('canceled-join')
                            {{ __('I canceled my participation in the event.') }}
                        @break

                        @case('cannot-delete-event')
                            {{ __('Failed to delete event.') }}
                        @break

                        @case('event-updated')
                            {{ __('Updated event information.') }}
                        @break

                        @case('unauthorized')
                            {{ __('This request is invalid.') }}
                        @break

                        @case('changed-status')
                            {{ __('Changed participation status.') }}
                        @break

                        @case('not-change-status')
                            {{ __('It could not be changed.') }}
                        @break

                        @case('no-change')
                            {{ __('It could not be changed.') }}
                        @break

                        @case('cancel-not-allowed')
                            {{ __('Rejected events cannot be canceled.') }}
                        @break

                        @case('event-create')
                            {{ __('Saved.') }}
                        @break

                        @case('deadline-passed')
                            {{ __('Deadline has passed.') }}
                        @break

                        @case('event-deleted')
                            {{ __('Deleted the event.') }}
                        @break

                        @case('password-updated')
                            {{ __('Saved.') }}
                        @break

                        @case('profile-updated')
                            {{ __('Saved.') }}
                        @break

                        @default
                            {{ session('status') }}
                    @endswitch
                </p>
            </div>
        </div>
        <div class="mt-6 flex justify-center space-x-4 mb-4">
            <x-secondary-button class="mt-2" x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </x-modal>
@endif
