<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DonationConfirmedMail;
use App\Models\Donation;
use App\Services\PointService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(Request $request): View
    {
        $donations = Donation::with(['campaign', 'user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'pending' => Donation::where('status', 'pending')->count(),
            'terkonfirmasi' => Donation::where('status', 'terkonfirmasi')->count(),
            'ditolak' => Donation::where('status', 'ditolak')->count(),
        ];

        return view('admin.donations.index', compact('donations', 'stats'));
    }

    public function confirm(Donation $donation, PointService $points): RedirectResponse
    {
        $donation->update(['status' => 'terkonfirmasi']);

        if ($donation->user) {
            $points->award($donation->user, 'donation', $donation->id, PointService::POINTS_DONATION_CONFIRMED);
        }

        send_mail_safely($donation->notifyEmail(), new DonationConfirmedMail($donation));

        return back()->with('success', 'Donasi dari '.$donation->donor_name.' berhasil dikonfirmasi.');
    }

    public function reject(Donation $donation): RedirectResponse
    {
        $donation->update(['status' => 'ditolak']);

        return back()->with('success', 'Donasi dari '.$donation->donor_name.' ditolak.');
    }
}
