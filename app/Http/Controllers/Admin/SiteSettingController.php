<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'org_name' => ['required', 'string', 'max:150'],
            'org_tagline' => ['nullable', 'string', 'max:255'],
            'org_description' => ['nullable', 'string', 'max:500'],
            'org_hashtag_tagline' => ['nullable', 'string', 'max:100'],
            'theme_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_whatsapp' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:512'],
            'hero_image' => ['nullable', 'image', 'max:2048'],
            'qris_image' => ['nullable', 'image', 'max:2048'],
            'mail_host' => ['nullable', 'string', 'max:150'],
            'mail_port' => ['nullable', 'integer', 'between:1,65535'],
            'mail_encryption' => ['nullable', 'in:tls,ssl,none'],
            'mail_username' => ['nullable', 'string', 'max:150'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_from_address' => ['nullable', 'email', 'max:150'],
            'mail_from_name' => ['nullable', 'string', 'max:150'],
        ]);

        $values = collect($validated)->except(['logo', 'favicon', 'hero_image', 'qris_image', 'mail_password'])->all();

        foreach (['logo', 'favicon', 'hero_image', 'qris_image'] as $field) {
            if ($request->hasFile($field)) {
                $values[$field] = $request->file($field)->store('branding', 'public');
            }
        }

        if ($request->filled('mail_password')) {
            $values['mail_password'] = Crypt::encryptString($request->string('mail_password')->toString());
        }

        SiteSetting::setMany($values);

        return back()->with('success', 'Pengaturan situs berhasil disimpan.');
    }

    public function testMail(Request $request): RedirectResponse
    {
        if (! site_setting('mail_host')) {
            return back()->with('error', 'Isi dan simpan pengaturan SMTP dulu sebelum kirim email test.');
        }

        try {
            Mail::to($request->user()->email)->send(new TestMail());
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal kirim email test: '.$e->getMessage());
        }

        return back()->with('success', 'Email test terkirim ke '.$request->user()->email.'. Cek inbox (atau folder spam).');
    }
}
