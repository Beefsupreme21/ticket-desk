<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tickets') }}
            </h2>

            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('New Ticket') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($tickets->isEmpty())
                        <p>{{ __('No tickets yet.') }}</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Subject') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Assigned To') }}
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Created By') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($tickets as $ticket)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $ticket->subject }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $ticket->status->label() }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $ticket->assignee?->name ?? __('Unassigned') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $ticket->creator->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
