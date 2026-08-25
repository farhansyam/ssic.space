<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['email', 'phone', 'subscribed_at', 'is_active'])]
class NewsletterSubscriber extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
