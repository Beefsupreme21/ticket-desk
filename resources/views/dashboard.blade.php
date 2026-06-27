<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if (Auth::user()->canManageTickets())
                        <p>{{ __('Manage the ticket queue from the tickets page.') }}</p>
                        <p class="mt-4">
                            <a href="{{ route('tickets.index') }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ __('View tickets') }}
                            </a>
                        </p>
                    @elseif (Auth::user()->isAdvisor())
                        <p>{{ __('Accept the next available ticket to start working.') }}</p>

                        <form method="POST" action="{{ route('tickets.accept-next') }}" class="mt-4">
                            @csrf

                            <x-primary-button>
                                {{ __('Accept next ticket') }}
                            </x-primary-button>
                        </form>
                    @else
                        <p>{{ __("You're logged in!") }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
