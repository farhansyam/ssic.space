<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormFieldController extends Controller
{
    public function store(Request $request, Form $form): JsonResponse
    {
        $validated = $this->validated($request);

        $field = $form->fields()->create([
            ...$validated,
            'sort_order' => ($form->fields()->max('sort_order') ?? -1) + 1,
        ]);

        return response()->json($field);
    }

    public function update(Request $request, Form $form, FormField $field): JsonResponse
    {
        abort_unless($field->form_id === $form->id, 404);

        $field->update($this->validated($request));

        return response()->json($field);
    }

    public function destroy(Form $form, FormField $field): JsonResponse
    {
        abort_unless($field->form_id === $form->id, 404);

        $field->delete();

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request, Form $form): JsonResponse
    {
        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($validated['order'] as $index => $fieldId) {
            FormField::where('id', $fieldId)->where('form_id', $form->id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:text,textarea,email,phone,select,radio,checkbox,file,date'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string', 'max:150'],
            'is_required' => ['nullable', 'boolean'],
        ]);

        $needsOptions = in_array($validated['type'], ['select', 'radio', 'checkbox'], true);
        $validated['options_json'] = $needsOptions ? array_values(array_filter($validated['options'] ?? [])) : null;
        $validated['is_required'] = $request->boolean('is_required');
        unset($validated['options']);

        return $validated;
    }
}
