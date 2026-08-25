<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;
use App\Models\FundDisbursement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FundDisbursementController extends Controller
{
    public function index(DonationCampaign $campaign): View
    {
        $campaign->load('disbursements');

        return view('admin.fund-disbursements.index', compact('campaign'));
    }

    public function store(Request $request, DonationCampaign $campaign): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['proof_image'] = $request->file('proof_image')?->store('disbursements', 'public');

        $campaign->disbursements()->create($validated);

        return back()->with('success', 'Penyaluran dana berhasil dicatat.');
    }

    public function destroy(DonationCampaign $campaign, FundDisbursement $disbursement): RedirectResponse
    {
        $disbursement->delete();

        return back()->with('success', 'Catatan penyaluran dana berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'description' => ['required', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:0'],
            'proof_image' => ['nullable', 'image', 'max:2048'],
            'disbursed_at' => ['required', 'date'],
        ]);
    }
}
