<?php

namespace App\Http\Requests;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Ticket $ticket */
        $ticket = $this->route('ticket');

        return $this->user()->can('update', $ticket);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $rules = [
            'status' => ['required', Rule::enum(TicketStatus::class)],
        ];

        if ($this->user()->canManageTickets()) {
            $rules['assigned_to'] = ['nullable', 'exists:users,id'];
        }

        return $rules;
    }
}
