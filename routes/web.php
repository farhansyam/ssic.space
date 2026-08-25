<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CertificateVerifyController;
use App\Http\Controllers\Public\DonationController;
use App\Http\Controllers\Public\EventController;
use App\Http\Controllers\Public\FormController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\KelasController;
use App\Http\Controllers\Public\LeaderboardController;
use App\Http\Controllers\Public\NewsletterController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\RecruitmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/manifest.json', [PwaController::class, 'manifest'])->name('pwa.manifest');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::prefix('kelas')->name('kelas.')->group(function () {
    Route::get('/', [KelasController::class, 'index'])->name('index');
    Route::get('/{kelas:slug}', [KelasController::class, 'show'])->name('show');
    Route::post('/{kelas:slug}/daftar', [KelasController::class, 'register'])->name('register')->middleware('auth');
});

Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{event:slug}', [EventController::class, 'show'])->name('show');
    Route::post('/{event:slug}/daftar', [EventController::class, 'register'])->name('register');
});

Route::prefix('donasi')->name('donasi.')->group(function () {
    Route::get('/', [DonationController::class, 'index'])->name('index');
    Route::get('/umum', [DonationController::class, 'general'])->name('umum');
    Route::post('/umum', [DonationController::class, 'storeGeneral'])->name('umum.store');
    Route::get('/{campaign:slug}', [DonationController::class, 'show'])->name('show');
    Route::post('/{campaign:slug}', [DonationController::class, 'store'])->name('store');
});

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
});

Route::get('/form/{form:slug}', [FormController::class, 'show'])->name('form.show');
Route::post('/form/{form:slug}', [FormController::class, 'submit'])->name('form.submit');
Route::get('/respons/{token}', [FormController::class, 'responses'])->name('form.responses');
Route::get('/respons/{token}/export', [FormController::class, 'responsesExport'])->name('form.responses.export');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/recruitment', [RecruitmentController::class, 'index'])->name('recruitment');

Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

Route::get('/sertifikat/{number}', [CertificateVerifyController::class, 'show'])->name('sertifikat.verify');
