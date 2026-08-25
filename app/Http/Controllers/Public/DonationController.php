<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCampaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function index(): View
    {
        $campaigns = DonationCampaign::withCount('donations')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('public.donations.index', compact('campaigns'));
    }

    public function show(DonationCampaign $campaign): View
    {
        $campaign->load('seoMeta', 'disbursements');

        return view('public.donations.show', compact('campaign'));
    }

    public function store(Request $request, DonationCampaign $campaign): RedirectResponse
    {
        $donation = $this->createDonation($request, $campaign->id);

        return redirect()->route('donasi.show', $campaign)->with('success', 'Terima kasih, '.$donation->donor_name.'! Donasimu sedang diverifikasi admin.');
    }

    public function general(): View
    {
        return view('public.donations.general');
    }

    public function storeGeneral(Request $request): RedirectResponse
    {
        $donation = $this->createDonation($request, null);

        return redirect()->route('donasi.umum')->with('success', 'Terima kasih, '.$donation->donor_name.'! Donasimu sedang diverifikasi admin.');
    }

    private function createDonation(Request $request, ?int $campaignId): Donation
    {
        $validated = $request->validate([
            'is_anonymous' => ['nullable', 'boolean'],
            'donor_name' => ['nullable', 'string', 'max:150'],
            'donor_email' => ['nullable', 'email', 'max:150'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'payment_method' => ['required', 'string', 'max:50'],
            'proof_image' => ['nullable', 'image', 'max:2048'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $donorName = $request->boolean('is_anonymous') || empty($validated['donor_name'])
            ? 'Hamba Allah'
            : $validated['donor_name'];

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('donations', 'public');
        }

        return Donation::create([
            'campaign_id' => $campaignId,
            'user_id' => $request->user()?->id,
            'donor_name' => $donorName,
            'donor_email' => $validated['donor_email'] ?? $request->user()?->email,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'proof_image' => $proofPath,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);
    }
}
