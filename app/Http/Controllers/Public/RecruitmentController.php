<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\View\View;

class RecruitmentController extends Controller
{
    public function index(): View
    {
        $forms = Form::where('target_type', 'recruitment')
            ->orderByDesc('created_at')
            ->get();

        return view('public.recruitment.index', compact('forms'));
    }
}
