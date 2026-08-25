<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['image', 'image_focal_x', 'image_focal_y', 'title', 'subtitle', 'cta_text', 'cta_link', 'sort_order', 'is_active'])]
class HeroBanner extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
