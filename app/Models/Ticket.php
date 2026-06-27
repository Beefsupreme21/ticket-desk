<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['subject', 'message', 'status', 'assigned_to', 'created_by'])]
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @param  Builder<Ticket>  $query
     */
    public function scopeUnassigned(Builder $query): void
    {
        $query->whereNull('assigned_to')
            ->where('status', TicketStatus::Open);
    }

    /**
     * @param  Builder<Ticket>  $query
     */
    public function scopeVisibleTo(Builder $query, User $user): void
    {
        if ($user->canManageTickets()) {
            return;
        }

        $query->where('assigned_to', $user->id);
    }
}
