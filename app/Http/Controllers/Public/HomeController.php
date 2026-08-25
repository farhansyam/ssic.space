<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\DonationCampaign;
use App\Models\Event;
use App\Models\HeroBanner;
use App\Models\InstagramPost;
use App\Models\Kelas;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroBanners = HeroBanner::where('is_active', true)->orderBy('sort_order')->get();
        $instagramPosts = InstagramPost::orderByDesc('posted_at')->limit(8)->get();
        $testimonials = Testimonial::orderByDesc('created_at')->limit(6)->get();
        $partners = Partner::orderByDesc('created_at')->get();

        $divisions = Division::withCount('users')->orderByDesc('id')->limit(3)->get();
        $kelasList = Kelas::whereIn('status', ['dibuka', 'penuh'])->orderByDesc('created_at')->limit(3)->get();
        $eventList = Event::where('status', 'upcoming')->orderBy('event_date')->limit(3)->get();
        $campaignList = DonationCampaign::orderByDesc('created_at')->limit(3)->get();
        $postList = Post::where('status', 'publish')->with('category')->orderByDesc('published_at')->limit(3)->get();

        $stats = [
            'members' => User::count(),
            'events' => Event::where('status', 'selesai')->count(),
            'classes' => Kelas::whereIn('status', ['dibuka', 'penuh'])->count(),
        ];

        return view('welcome', compact(
            'heroBanners', 'instagramPosts', 'testimonials', 'partners', 'stats',
            'divisions', 'kelasList', 'eventList', 'campaignList', 'postList'
        ));
    }
}
