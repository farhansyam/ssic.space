<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;

class ShortLinkRedirectController extends Controller
{
    public function __invoke(string $slug): RedirectResponse
    {
        $link = ShortLink::where('slug', $slug)->firstOrFail();
        $link->increment('click_count');

        return redirect()->away($link->target_url);
    }
}
