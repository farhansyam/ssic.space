<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ClassRegistration;
use App\Models\EventRegistration;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\RecruitmentApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormController extends Controller
{
    public function show(Form $form): View
    {
        $form->load('fields', 'target');

        return view('public.forms.show', compact('form'));
    }

    public function responses(string $token): View
    {
        $form = Form::where('share_token', $token)->firstOrFail();
        $form->load('fields');
        $submissions = $form->submissions()->latest()->paginate(20);

        return view('public.forms.responses', compact('form', 'submissions', 'token'));
    }

    public function responsesExport(string $token): StreamedResponse
    {
        $form = Form::where('share_token', $token)->firstOrFail();
        $form->load('fields');
        $submissions = $form->submissions()->orderBy('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$form->slug.'-submissions.csv"',
        ];

        $callback = function () use ($form, $submissions) {
            $out = fopen('php://output', 'w');
            fputcsv($out, array_merge(['Tanggal'], $form->fields->pluck('label')->all()), escape: '');

            foreach ($submissions as $submission) {
                $row = [$submission->created_at->format('Y-m-d H:i')];
                foreach ($form->fields as $field) {
                    $value = $submission->data_json[$field->id] ?? '';
                    $row[] = is_array($value) ? implode(', ', $value) : $value;
                }
                fputcsv($out, $row, escape: '');
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function submit(Request $request, Form $form): RedirectResponse
    {
        $form->load('fields', 'target');

        $rules = [];
        foreach ($form->fields as $field) {
            $key = 'field_'.$field->id;
            $fieldRules = [$field->is_required ? 'required' : 'nullable'];
            $fieldRules[] = match ($field->type) {
                'email' => 'email',
                'date' => 'date',
                'file' => 'file|max:2048',
                'audio' => 'file|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/mp4,audio/ogg|max:10240',
                'checkbox' => 'array',
                default => 'string',
            };
            $rules[$key] = $fieldRules;
        }
        $validated = $request->validate($rules);

        $data = [];
        foreach ($form->fields as $field) {
            $key = 'field_'.$field->id;
            if (in_array($field->type, ['file', 'audio'], true) && $request->hasFile($key)) {
                $data[$field->id] = $request->file($key)->store('form-submissions', 'public');
            } else {
                $data[$field->id] = $validated[$key] ?? null;
            }
        }

        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'user_id' => $request->user()?->id,
            'data_json' => $data,
        ]);

        $this->registerToTarget($form, $request);

        if ($form->target_type === 'recruitment') {
            RecruitmentApplication::create([
                'form_submission_id' => $submission->id,
                'status' => 'submitted',
            ]);
        }

        if ($form->notify_email) {
            Mail::raw('Ada respons baru untuk form "'.$form->name.'". Cek panel admin untuk detail.', function ($message) use ($form) {
                $message->to($form->notify_email)->subject('Respons Baru: '.$form->name);
            });
        }

        return redirect()->route('form.show', $form)->with('form_submitted', $form->id);
    }

    private function registerToTarget(Form $form, Request $request): void
    {
        if (! $form->target || ! $request->user()) {
            return;
        }

        if ($form->target_type === 'kelas') {
            ClassRegistration::firstOrCreate(
                ['class_id' => $form->target_id, 'user_id' => $request->user()->id],
                ['status' => 'terdaftar']
            );
        } elseif ($form->target_type === 'event') {
            EventRegistration::firstOrCreate(
                ['event_id' => $form->target_id, 'user_id' => $request->user()->id],
                ['status' => 'terdaftar']
            );
        }
    }
}
