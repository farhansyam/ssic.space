<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'user_id', 'guest_name', 'guest_email', 'guest_phone', 'status'])]
class EventRegistration extends Model
{
    const UPDATED_AT = null;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function displayName(): string
    {
        return $this->user?->name ?? $this->guest_name ?? 'Tanpa nama';
    }

    public function displayEmail(): string
    {
        return $this->user?->email ?? $this->guest_email ?? '-';
    }
}
