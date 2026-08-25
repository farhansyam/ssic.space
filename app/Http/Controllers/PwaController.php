<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class PwaController extends Controller
{
    public function manifest(): JsonResponse
    {
        $orgName = site_setting('org_name', 'SSIC');
        $themeColor = site_setting('theme_color', '#2474D2');

        return response()->json([
            'name' => $orgName.' — Synergy Social Impact Community',
            'short_name' => $orgName,
            'description' => 'Kelas, kegiatan, dan donasi bersama komunitas '.$orgName.'.',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => $themeColor,
            'icons' => [
                ['src' => '/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable'],
                ['src' => '/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
            ],
        ])->header('Content-Type', 'application/manifest+json');
    }
}
