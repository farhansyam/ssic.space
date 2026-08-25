<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable(['title', 'slug', 'description', 'event_date', 'location', 'pj_name', 'pj_whatsapp', 'image', 'image_focal_x', 'image_focal_y', 'status', 'registration_type'])]
class Event extends Model
{
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function pjWhatsappLink(): ?string
    {
        if (! $this->pj_whatsapp) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $this->pj_whatsapp);
        $digits = preg_replace('/^0/', '62', $digits);
        $message = rawurlencode('Halo, saya mau tanya soal kegiatan "'.$this->title.'".');

        return 'https://wa.me/'.$digits.'?text='.$message;
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function registrationForm(): MorphOne
    {
        return $this->morphOne(Form::class, 'target');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function activeRegistrations(): HasMany
    {
        return $this->registrations()->where('status', '!=', 'batal');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(EventGallery::class);
    }

    public function isRegisteredBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->activeRegistrations()->where('user_id', $user->id)->exists();
    }

    public function allowsGuestRegistration(): bool
    {
        return $this->registration_type === 'umum';
    }
}
