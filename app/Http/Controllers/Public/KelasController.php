<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationConfirmedMail;
use App\Models\ClassRegistration;
use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $classes = Kelas::withCount('activeRegistrations')
            ->whereIn('status', ['dibuka', 'penuh'])
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->string('kategori')))
            ->when($request->filled('level'), fn ($q) => $q->where('level', $request->string('level')))
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.kelas.index', compact('classes'));
    }

    public function show(Kelas $kelas): View
    {
        $kelas->loadCount('activeRegistrations');
        $kelas->load('seoMeta', 'registrationForm');

        return view('public.kelas.show', compact('kelas'));
    }

    public function register(Request $request, Kelas $kelas): RedirectResponse
    {
        $user = $request->user();

        if ($kelas->status !== 'dibuka') {
            return back()->with('error', 'Pendaftaran kelas ini belum/tidak dibuka.');
        }

        if ($kelas->isRegisteredBy($user)) {
            return back()->with('error', 'Kamu sudah terdaftar di kelas ini.');
        }

        if ($kelas->isFull()) {
            return back()->with('error', 'Kuota kelas ini sudah penuh.');
        }

        ClassRegistration::create([
            'class_id' => $kelas->id,
            'user_id' => $user->id,
            'status' => 'terdaftar',
        ]);

        if ($kelas->capacity > 0 && $kelas->activeRegistrations()->count() >= $kelas->capacity) {
            $kelas->update(['status' => 'penuh']);
        }

        send_mail_safely($user->email, new RegistrationConfirmedMail($user->name, $kelas->title, 'Kelas', route('kelas.show', $kelas)));

        return back()->with('success', 'Berhasil daftar ke kelas "'.$kelas->title.'"!');
    }
}
