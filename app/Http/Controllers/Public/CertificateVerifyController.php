<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\View\View;

class CertificateVerifyController extends Controller
{
    public function show(string $number): View
    {
        $certificate = Certificate::with(['user', 'certifiable'])
            ->where('certificate_number', $number)
            ->first();

        return view('public.certificates.verify', compact('certificate', 'number'));
    }
}
