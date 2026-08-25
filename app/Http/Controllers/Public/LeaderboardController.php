<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PointLog;
use App\Models\User;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(): View
    {
        $rankings = User::where('role', 'member')
            ->withSum('pointLogs', 'points')
            ->with('userBadges.badge')
            ->orderByDesc('point_logs_sum_points')
            ->take(50)
            ->get()
            ->filter(fn (User $user) => $user->point_logs_sum_points > 0)
            ->values();

        $currentUser = auth()->user();
        $myRank = null;
        $myPoints = 0;

        if ($currentUser) {
            $myPoints = $currentUser->totalPoints();
            $myRank = $myPoints > 0
                ? PointLog::selectRaw('user_id, SUM(points) as total')->groupBy('user_id')->having('total', '>', $myPoints)->get()->count() + 1
                : null;
        }

        return view('public.leaderboard.index', compact('rankings', 'currentUser', 'myRank', 'myPoints'));
    }
}
