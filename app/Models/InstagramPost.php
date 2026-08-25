<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ig_media_id', 'caption', 'media_url', 'permalink', 'posted_at', 'synced_at'])]
class InstagramPost extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
            'synced_at' => 'datetime',
        ];
    }
}
