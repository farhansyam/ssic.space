<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['form_submission_id', 'status', 'status_note', 'updated_by'])]
class RecruitmentApplication extends Model
{
    public function formSubmission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function applicantName(): string
    {
        if ($this->formSubmission->user) {
            return $this->formSubmission->user->name;
        }

        return $this->guessFieldValue(['nama', 'name']) ?? 'Pelamar #'.$this->formSubmission->id;
    }

    public function applicantEmail(): ?string
    {
        if ($this->formSubmission->user) {
            return $this->formSubmission->user->email;
        }

        return $this->guessFieldValue(['email']);
    }

    private function guessFieldValue(array $labelKeywords): ?string
    {
        $fields = $this->formSubmission->form->fields;
        $data = $this->formSubmission->data_json;

        foreach ($fields as $field) {
            foreach ($labelKeywords as $keyword) {
                if (str_contains(strtolower($field->label), $keyword)) {
                    $value = $data[$field->id] ?? null;

                    return is_array($value) ? implode(', ', $value) : $value;
                }
            }
        }

        return null;
    }
}
