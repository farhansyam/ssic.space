<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $user->load('division');

        $certificates = $user->certificates()->with('certifiable')->orderByDesc('issued_at')->get();
        $badges = $user->userBadges()->with('badge')->orderByDesc('earned_at')->get();
        $classRegistrations = $user->classRegistrations()->with('kelas')->latest()->get();
        $eventRegistrations = $user->eventRegistrations()->with('event')->latest()->get();

        $rank = \App\Models\User::withSum('pointLogs', 'points')
            ->having('point_logs_sum_points', '>', $user->totalPoints())
            ->count() + 1;

        $divisions = Division::orderBy('name')->get();

        return view('public.profile.index', compact(
            'user', 'certificates', 'badges', 'classRegistrations', 'eventRegistrations', 'rank', 'divisions'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password berhasil diganti.');
    }
}
