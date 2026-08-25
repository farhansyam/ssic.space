<?php

namespace App\Providers;

use App\Models\DonationCampaign;
use App\Models\Event;
use App\Models\Kelas;
use App\Models\Post;
use App\Models\RecruitmentApplication;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'kelas' => Kelas::class,
            'event' => Event::class,
            'donation_campaign' => DonationCampaign::class,
            'post' => Post::class,
            'recruitment' => RecruitmentApplication::class,
        ]);

        $this->applyMailSettingsFromDatabase();
    }

    private function applyMailSettingsFromDatabase(): void
    {
        try {
            $host = SiteSetting::get('mail_host');
        } catch (\Throwable) {
            return;
        }

        if (! $host) {
            return;
        }

        $password = SiteSetting::get('mail_password');
        if ($password) {
            try {
                $password = Crypt::decryptString($password);
            } catch (\Throwable) {
                $password = null;
            }
        }

        $encryption = SiteSetting::get('mail_encryption', 'tls');

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $host,
            'mail.mailers.smtp.port' => (int) SiteSetting::get('mail_port', '587'),
            'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            'mail.mailers.smtp.username' => SiteSetting::get('mail_username'),
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => SiteSetting::get('mail_from_address') ?: config('mail.from.address'),
            'mail.from.name' => SiteSetting::get('mail_from_name') ?: config('mail.from.name'),
        ]);
    }
}
