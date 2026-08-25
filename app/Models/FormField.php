<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['form_id', 'label', 'type', 'options_json', 'is_required', 'sort_order'])]
class FormField extends Model
{
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'options_json' => 'array',
            'is_required' => 'boolean',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function options(): array
    {
        return $this->options_json ?? [];
    }
}
