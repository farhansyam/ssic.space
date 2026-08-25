<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['certificate_number', 'user_id', 'certifiable_type', 'certifiable_id', 'issued_at', 'pdf_path'])]
class Certificate extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
