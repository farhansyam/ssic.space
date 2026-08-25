<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index(Request $request): View
    {
        $subscribers = NewsletterSubscriber::when($request->filled('q'), fn ($q) => $q->where('email', 'like', '%'.$request->string('q').'%'))
            ->orderByDesc('subscribed_at')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
        ];

        return view('admin.newsletter.index', compact('subscribers', 'stats'));
    }

    public function toggle(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->update(['is_active' => ! $subscriber->is_active]);

        return back()->with('success', 'Status subscriber berhasil diubah.');
    }

    public function destroy(NewsletterSubscriber $subscriber): RedirectResponse
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber berhasil dihapus.');
    }

    public function exportCsv(): StreamedResponse
    {
        $subscribers = NewsletterSubscriber::orderBy('subscribed_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="newsletter-subscribers.csv"',
        ];

        $callback = function () use ($subscribers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'No. HP', 'Tanggal Subscribe', 'Status'], escape: '');

            foreach ($subscribers as $subscriber) {
                fputcsv($out, [
                    $subscriber->email,
                    $subscriber->phone,
                    $subscriber->subscribed_at?->format('Y-m-d H:i'),
                    $subscriber->is_active ? 'Aktif' : 'Nonaktif',
                ], escape: '');
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
