<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

abstract class Controller
{
    /**
     * Save/update the polymorphic SEO meta for a model, given validated
     * `meta_title` / `meta_description` input and an optional og image path.
     */
    protected function saveSeoMeta(Model $model, ?string $metaTitle, ?string $metaDescription, ?string $ogImage = null): void
    {
        if (! $metaTitle && ! $metaDescription) {
            return;
        }

        $model->seoMeta()->updateOrCreate([], [
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'og_image' => $ogImage,
        ]);
    }
}
