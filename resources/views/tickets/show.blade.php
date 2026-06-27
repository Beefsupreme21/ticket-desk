<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $ticket->subject }}
            </h2>

            @can('complete', $ticket)
                <form method="POST" action="{{ route('tickets.complete', $ticket) }}">
                    @csrf

                    <x-primary-button>
                        {{ __('Mark as completed') }}
                    </x-primary-button>
                </form>
            @endcan

            @can('update', $ticket)
                @if (Auth::user()->canManageTickets())
                    <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                        {{ __('Edit') }}
                    </a>
                @endif
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Status') }}</h3>
                        <p>{{ $ticket->status->label() }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Assigned To') }}</h3>
                        <p>{{ $ticket->assignee?->name ?? __('Unassigned') }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Created By') }}</h3>
                        <p>{{ $ticket->creator->name }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">{{ __('Message') }}</h3>
                        <p class="whitespace-pre-wrap">{{ $ticket->message }}</p>
                    </div>

                    <div>
                        @if (Auth::user()->canManageTickets())
                            <a href="{{ route('tickets.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Back to tickets') }}
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Back to dashboard') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
