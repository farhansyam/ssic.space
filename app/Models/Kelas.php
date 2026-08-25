<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable(['title', 'slug', 'description', 'category', 'level', 'capacity', 'location', 'schedule', 'pj_name', 'pj_whatsapp', 'image', 'image_focal_x', 'image_focal_y', 'status'])]
class Kelas extends Model
{
    protected $table = 'classes';

    public function pjWhatsappLink(): ?string
    {
        if (! $this->pj_whatsapp) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $this->pj_whatsapp);
        $digits = preg_replace('/^0/', '62', $digits);
        $message = rawurlencode('Halo, saya mau tanya soal kelas "'.$this->title.'".');

        return 'https://wa.me/'.$digits.'?text='.$message;
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(ClassRegistration::class, 'class_id');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function registrationForm(): MorphOne
    {
        return $this->morphOne(Form::class, 'target');
    }

    public function activeRegistrations(): HasMany
    {
        return $this->registrations()->where('status', '!=', 'batal');
    }

    public function isFull(): bool
    {
        return $this->capacity > 0 && $this->activeRegistrations()->count() >= $this->capacity;
    }

    public function isRegisteredBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->activeRegistrations()->where('user_id', $user->id)->exists();
    }
}
