<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'background_image', 'layout_json'])]
class CertificateTemplate extends Model
{
    protected function casts(): array
    {
        return [
            'layout_json' => 'array',
        ];
    }
}
