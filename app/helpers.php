<?php

use App\Models\SiteSetting;

if (! function_exists('site_setting')) {
    function site_setting(string $key, ?string $default = null): ?string
    {
        return SiteSetting::get($key, $default);
    }
}

if (! function_exists('mail_is_configured')) {
    function mail_is_configured(): bool
    {
        return (bool) SiteSetting::get('mail_host');
    }
}

if (! function_exists('send_mail_safely')) {
    function send_mail_safely(?string $to, \Illuminate\Mail\Mailable $mailable): void
    {
        if (! $to || ! mail_is_configured()) {
            return;
        }

        try {
            \Illuminate\Support\Facades\Mail::to($to)->send($mailable);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
