<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'phone' => $validated['phone'] ?? null,
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        return back()->with('success', 'Terima kasih sudah berlangganan! Kami akan kabari update terbaru dari SSIC.');
    }
}
