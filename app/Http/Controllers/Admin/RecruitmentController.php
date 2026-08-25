<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecruitmentApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecruitmentController extends Controller
{
    public function index(): View
    {
        $applications = RecruitmentApplication::with('formSubmission.form.fields', 'formSubmission.user')
            ->latest()
            ->get()
            ->map(fn (RecruitmentApplication $app) => [
                'id' => $app->id,
                'status' => $app->status,
                'status_note' => $app->status_note,
                'applicant_name' => $app->applicantName(),
                'applicant_email' => $app->applicantEmail(),
                'form_name' => $app->formSubmission->form->name,
                'submitted_at' => $app->created_at->translatedFormat('d M Y, H:i'),
                'answers' => $app->formSubmission->form->fields->map(fn ($field) => [
                    'label' => $field->label,
                    'value' => is_array($app->formSubmission->data_json[$field->id] ?? null)
                        ? implode(', ', $app->formSubmission->data_json[$field->id])
                        : ($app->formSubmission->data_json[$field->id] ?? '-'),
                ]),
            ]);

        $columns = [
            'submitted' => 'Baru Masuk',
            'interview' => 'Interview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
        ];

        return view('admin.recruitment.index', compact('applications', 'columns'));
    }

    public function updateStatus(Request $request, RecruitmentApplication $recruitment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:submitted,interview,accepted,rejected'],
            'status_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $recruitment->update([
            ...$validated,
            'updated_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Status pelamar berhasil diperbarui.');
    }
}
