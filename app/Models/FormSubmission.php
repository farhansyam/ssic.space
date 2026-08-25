<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['form_id', 'user_id', 'data_json'])]
class FormSubmission extends Model
{
    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'data_json' => 'array',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recruitmentApplication(): HasOne
    {
        return $this->hasOne(RecruitmentApplication::class);
    }
}
