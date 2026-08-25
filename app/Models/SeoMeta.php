<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['seoable_type', 'seoable_id', 'meta_title', 'meta_description', 'og_image'])]
class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
