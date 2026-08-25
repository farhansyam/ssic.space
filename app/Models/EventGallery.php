<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'image_path', 'caption'])]
class EventGallery extends Model
{
    const UPDATED_AT = null;

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
