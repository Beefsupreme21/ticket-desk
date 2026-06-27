<?php

namespace Database\Seeders;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Seed sample tickets for demo and testing.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', config('demo.email'))->first();
        $manager = User::query()->where('email', 'manager@example.com')->first();
        $associate = User::query()->where('email', 'associate@example.com')->first();

        if (! $admin || ! $manager || ! $associate) {
            return;
        }

        $tickets = [
            [
                'subject' => 'Printer not working',
                'message' => 'The office printer on the second floor will not connect to the network.',
                'status' => TicketStatus::Open,
                'assigned_to' => null,
                'created_by' => $manager->id,
            ],
            [
                'subject' => 'Password reset needed',
                'message' => 'I locked myself out of my account and need a password reset.',
                'status' => TicketStatus::InProgress,
                'assigned_to' => $associate->id,
                'created_by' => $admin->id,
            ],
            [
                'subject' => 'VPN access request',
                'message' => 'Please set up VPN access for remote work next week.',
                'status' => TicketStatus::Resolved,
                'assigned_to' => $associate->id,
                'created_by' => $manager->id,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::query()->updateOrCreate(
                [
                    'subject' => $ticket['subject'],
                    'created_by' => $ticket['created_by'],
                ],
                $ticket,
            );
        }
    }
}
