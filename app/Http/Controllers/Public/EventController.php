<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationConfirmedMail;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::withCount('activeRegistrations')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderBy('event_date', request('status') === 'selesai' ? 'desc' : 'asc')
            ->paginate(9)
            ->withQueryString();

        return view('public.events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $event->loadCount('activeRegistrations');
        $event->load('galleries', 'seoMeta', 'registrationForm');

        return view('public.events.show', compact('event'));
    }

    public function register(Request $request, Event $event): RedirectResponse
    {
        if ($event->status !== 'upcoming') {
            return back()->with('error', 'Pendaftaran kegiatan ini sudah ditutup.');
        }

        if ($request->user()) {
            if ($event->isRegisteredBy($request->user())) {
                return back()->with('error', 'Kamu sudah terdaftar di kegiatan ini.');
            }

            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $request->user()->id,
                'status' => 'terdaftar',
            ]);

            send_mail_safely($request->user()->email, new RegistrationConfirmedMail($request->user()->name, $event->title, 'Kegiatan', route('kegiatan.show', $event)));

            return back()->with('success', 'Berhasil daftar ke kegiatan "'.$event->title.'"!');
        }

        if (! $event->allowsGuestRegistration()) {
            return back()->with('error', 'Kegiatan ini hanya untuk anggota. Silakan masuk terlebih dahulu.');
        }

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:150'],
            'guest_email' => ['required', 'email', 'max:150'],
            'guest_phone' => ['nullable', 'string', 'max:20'],
            'create_account' => ['nullable', 'boolean'],
            'password' => ['nullable', 'required_if:create_account,1', 'string', 'min:8', 'confirmed'],
        ]);

        $alreadyRegistered = EventRegistration::where('event_id', $event->id)
            ->where('guest_email', $validated['guest_email'])
            ->where('status', '!=', 'batal')
            ->exists();

        if ($alreadyRegistered) {
            return back()->with('error', 'Email ini sudah terdaftar di kegiatan ini.');
        }

        if ($request->boolean('create_account')) {
            if (User::where('email', $validated['guest_email'])->exists()) {
                return back()->with('error', 'Email ini sudah punya akun. Silakan masuk dulu untuk daftar.')->withInput();
            }

            $user = User::create([
                'name' => $validated['guest_name'],
                'email' => $validated['guest_email'],
                'phone' => $validated['guest_phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'member',
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'terdaftar',
            ]);

            send_mail_safely($user->email, new RegistrationConfirmedMail($user->name, $event->title, 'Kegiatan', route('kegiatan.show', $event)));

            return back()->with('success', 'Berhasil daftar & akun kamu sudah dibuat! Kamu sekarang login sebagai '.$user->name.'.');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'] ?? null,
            'status' => 'terdaftar',
        ]);

        send_mail_safely($validated['guest_email'], new RegistrationConfirmedMail($validated['guest_name'], $event->title, 'Kegiatan', route('kegiatan.show', $event)));

        return back()->with('success', 'Berhasil daftar ke kegiatan "'.$event->title.'"! Cek email kamu untuk info lebih lanjut.');
    }
}
