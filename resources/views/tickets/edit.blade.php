<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-6 font-medium">{{ $ticket->subject }}</p>

                    <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach (\App\Enums\TicketStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $ticket->status->value) === $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        @if (Auth::user()->canManageTickets())
                            <div class="mt-4">
                                <x-input-label for="assigned_to" :value="__('Assigned To')" />
                                <select id="assigned_to" name="assigned_to" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Unassigned') }}</option>
                                    @foreach ($assignees as $assignee)
                                        <option value="{{ $assignee->id }}" @selected(old('assigned_to', $ticket->assigned_to) == $assignee->id)>
                                            {{ $assignee->name }} ({{ $assignee->role->label() }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('tickets.show', $ticket) }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>

                            <x-primary-button class="ms-3">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
