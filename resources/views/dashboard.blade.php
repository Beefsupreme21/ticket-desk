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
                    @if (Auth::user()->isAdmin() && config('demo.errors_enabled'))
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Nightwatch demo errors') }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ __('Trigger realistic failures for monitoring practice.') }}
                            </p>

                            <div class="mt-4 space-y-3">
                                <form method="POST" action="{{ route('demo.errors.exception') }}">
                                    @csrf
                                    <x-secondary-button type="submit">
                                        {{ __('Unhandled exception') }}
                                    </x-secondary-button>
                                </form>

                                <form method="POST" action="{{ route('demo.errors.failed-job') }}">
                                    @csrf
                                    <x-secondary-button type="submit">
                                        {{ __('Failed notification job') }}
                                    </x-secondary-button>
                                </form>

                                <form method="POST" action="{{ route('demo.errors.webhook') }}">
                                    @csrf
                                    <x-secondary-button type="submit">
                                        {{ __('Failed webhook') }}
                                    </x-secondary-button>
                                </form>

                                <form method="POST" action="{{ route('demo.errors.missing-assignee') }}">
                                    @csrf
                                    <x-secondary-button type="submit">
                                        {{ __('Missing assignee bug') }}
                                    </x-secondary-button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
