<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\DonationCampaignController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\AnnouncementPopupController;
use App\Http\Controllers\Admin\BadgeController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\CertificateTemplateController;
use App\Http\Controllers\Admin\FormFieldController;
use App\Http\Controllers\Admin\FundDisbursementController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\InstagramPostController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\PostTagController;
use App\Http\Controllers\Admin\RecruitmentController;
use App\Http\Controllers\Admin\ShortLinkController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('members', UserController::class)->parameters(['members' => 'member'])->except(['show']);

Route::resource('divisions', DivisionController::class)->except(['show']);

Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas'])->except(['show']);
Route::get('kelas/{kelas}/peserta', [KelasController::class, 'participants'])->name('kelas.participants');
Route::put('kelas/{kelas}/peserta/{registration}/status', [KelasController::class, 'updateParticipantStatus'])->name('kelas.participants.status');
Route::post('kelas/{kelas}/peserta/{registration}/certificate', [CertificateController::class, 'issueForClass'])->name('kelas.participants.certificate');

Route::resource('events', EventController::class)->except(['show']);
Route::get('events/{event}/peserta', [EventController::class, 'participants'])->name('events.participants');
Route::put('events/{event}/peserta/{registration}/status', [EventController::class, 'updateParticipantStatus'])->name('events.participants.status');
Route::post('events/{event}/peserta/{registration}/certificate', [CertificateController::class, 'issueForEvent'])->name('events.participants.certificate');
Route::get('events/{event}/galeri', [EventController::class, 'gallery'])->name('events.gallery');
Route::post('events/{event}/galeri', [EventController::class, 'galleryStore'])->name('events.gallery.store');
Route::delete('events/{event}/galeri/{gallery}', [EventController::class, 'galleryDestroy'])->name('events.gallery.destroy');

Route::resource('donation-campaigns', DonationCampaignController::class)->parameters(['donation-campaigns' => 'campaign'])->except(['show']);
Route::get('donation-campaigns/{campaign}/disbursements', [FundDisbursementController::class, 'index'])->name('donation-campaigns.disbursements.index');
Route::post('donation-campaigns/{campaign}/disbursements', [FundDisbursementController::class, 'store'])->name('donation-campaigns.disbursements.store');
Route::delete('donation-campaigns/{campaign}/disbursements/{disbursement}', [FundDisbursementController::class, 'destroy'])->name('donation-campaigns.disbursements.destroy');

Route::get('donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('donations/{donation}/confirm', [DonationController::class, 'confirm'])->name('donations.confirm');
Route::post('donations/{donation}/reject', [DonationController::class, 'reject'])->name('donations.reject');

Route::resource('posts', PostController::class)->except(['show']);
Route::resource('post-categories', PostCategoryController::class)->parameters(['post-categories' => 'postCategory'])->only(['index', 'store', 'update', 'destroy']);
Route::resource('post-tags', PostTagController::class)->parameters(['post-tags' => 'postTag'])->only(['index', 'store', 'update', 'destroy']);

Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');
Route::post('settings/test-mail', [SiteSettingController::class, 'testMail'])->name('settings.test-mail');

Route::resource('forms', FormController::class)->except(['show']);
Route::get('forms/{form}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');
Route::post('forms/{form}/short-link', [FormController::class, 'shortLink'])->name('forms.short-link');
Route::post('forms/{form}/share/enable', [FormController::class, 'enableShare'])->name('forms.share.enable');
Route::post('forms/{form}/share/disable', [FormController::class, 'disableShare'])->name('forms.share.disable');
Route::get('forms/{form}/export', [FormController::class, 'exportCsv'])->name('forms.export');
Route::post('forms/{form}/fields', [FormFieldController::class, 'store'])->name('forms.fields.store');
Route::put('forms/{form}/fields/{field}', [FormFieldController::class, 'update'])->name('forms.fields.update');
Route::delete('forms/{form}/fields/{field}', [FormFieldController::class, 'destroy'])->name('forms.fields.destroy');
Route::post('forms/{form}/fields/reorder', [FormFieldController::class, 'reorder'])->name('forms.fields.reorder');

Route::resource('short-links', ShortLinkController::class)->parameters(['short-links' => 'shortLink'])->only(['index', 'store', 'update', 'destroy']);

Route::resource('hero-banners', HeroBannerController::class)->parameters(['hero-banners' => 'heroBanner'])->except(['show']);
Route::post('hero-banners/{heroBanner}/toggle', [HeroBannerController::class, 'toggle'])->name('hero-banners.toggle');
Route::post('hero-banners/reorder', [HeroBannerController::class, 'reorder'])->name('hero-banners.reorder');

Route::resource('popups', AnnouncementPopupController::class)->parameters(['popups' => 'popup'])->except(['show']);

Route::resource('instagram-posts', InstagramPostController::class)->parameters(['instagram-posts' => 'instagramPost'])->only(['index', 'store', 'destroy']);

Route::resource('testimonials', TestimonialController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('partners', PartnerController::class)->only(['index', 'store', 'destroy']);

Route::resource('badges', BadgeController::class)->only(['index', 'store', 'update', 'destroy']);

Route::resource('certificate-templates', CertificateTemplateController::class)->except(['show']);
Route::post('certificate-templates/preview', [CertificateTemplateController::class, 'preview'])->name('certificate-templates.preview');
Route::get('certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::post('certificates/download-batch', [CertificateController::class, 'downloadBatch'])->name('certificates.download-batch');

Route::get('recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
Route::put('recruitment/{recruitment}/status', [RecruitmentController::class, 'updateStatus'])->name('recruitment.status');

Route::get('newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
Route::get('newsletter/export', [NewsletterController::class, 'exportCsv'])->name('newsletter.export');
Route::post('newsletter/{subscriber}/toggle', [NewsletterController::class, 'toggle'])->name('newsletter.toggle');
Route::delete('newsletter/{subscriber}', [NewsletterController::class, 'destroy'])->name('newsletter.destroy');
