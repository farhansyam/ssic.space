<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable(['title', 'slug', 'description', 'target_amount', 'deadline', 'image'])]
class DonationCampaign extends Model
{
    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'deadline' => 'date',
        ];
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'campaign_id');
    }

    public function confirmedDonations(): HasMany
    {
        return $this->donations()->where('status', 'terkonfirmasi');
    }

    public function collectedAmount(): float
    {
        return (float) $this->confirmedDonations()->sum('amount');
    }

    public function progressPercent(): int
    {
        if ((float) $this->target_amount <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->collectedAmount() / (float) $this->target_amount) * 100));
    }

    public function disbursements(): HasMany
    {
        return $this->hasMany(FundDisbursement::class, 'campaign_id')->orderByDesc('disbursed_at');
    }

    public function disbursedAmount(): float
    {
        return (float) $this->disbursements()->sum('amount');
    }

    public function remainingAmount(): float
    {
        return max(0, $this->collectedAmount() - $this->disbursedAmount());
    }

    public function disbursedPercent(): int
    {
        $collected = $this->collectedAmount();

        if ($collected <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->disbursedAmount() / $collected) * 100));
    }
}
