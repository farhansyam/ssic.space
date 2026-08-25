<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRegistration;
use App\Models\Division;
use App\Models\Donation;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Kelas;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'members' => User::where('role', 'member')->count(),
            'divisions' => Division::count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'classes_active' => Kelas::where('status', 'dibuka')->count(),
            'events_upcoming' => Event::where('status', 'upcoming')->count(),
            'donations_total' => (float) Donation::where('status', 'terkonfirmasi')->sum('amount'),
            'donations_pending' => Donation::where('status', 'pending')->count(),
            'posts_published' => Post::where('status', 'publish')->count(),
        ];

        $chartLabels = [];
        $chartValues = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);
            $classCount = ClassRegistration::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();
            $eventCount = EventRegistration::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();

            $chartLabels[] = $month->translatedFormat('M');
            $chartValues[] = $classCount + $eventCount;
        }

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartValues'));
    }
}
