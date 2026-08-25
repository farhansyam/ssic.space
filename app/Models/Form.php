<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'name', 'slug', 'description', 'banner_image', 'font_family', 'theme_color',
    'target_type', 'target_id', 'notify_email',
    'confirmation_title', 'confirmation_message', 'confirmation_link_url', 'confirmation_link_label',
])]
class Form extends Model
{
    public const FONTS = [
        'sans' => 'Default (Red Hat Display)',
        'playful' => 'Playful (Fredoka)',
        'serif' => 'Klasik (Serif)',
        'mono' => 'Modern (Mono)',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
