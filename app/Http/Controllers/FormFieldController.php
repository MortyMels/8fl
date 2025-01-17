<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;

class FormFieldController extends Controller
{
    public function index(Form $form)
    {
        $fields = $form->fields()->orderBy('sort_order')->get();
        return view('form-fields.index', compact('fields', 'form'));
    }

    public function store(Request $request, Form $form)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,select,checkbox,radio,date,number',
            'required' => 'boolean',
            'options' => 'nullable|string',
            'sort_order' => 'integer'
        ]);

        if (!empty($validated['options'])) {
            $validated['options'] = explode("\n", $validated['options']);
        }

        $form->fields()->create($validated);

        return redirect()->back()->with('success', 'Поле формы успешно создано');
    }

    public function edit(Form $form, FormField $field)
    {
        return view('form-fields.edit', compact('form', 'field'));
    }

    public function update(Request $request, Form $form, FormField $field)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:text,textarea,select,checkbox,radio,date,number',
            'required' => 'boolean',
            'options' => 'nullable|string',
            'sort_order' => 'integer'
        ]);

        if (!empty($validated['options'])) {
            $validated['options'] = explode("\n", $validated['options']);
        }

        $field->update($validated);

        return redirect()->route('forms.fields.index', $form)
            ->with('success', 'Поле формы успешно обновлено');
    }

    public function destroy(Form $form, FormField $field)
    {
        $field->delete();
        return redirect()->back()->with('success', 'Поле формы успешно удалено');
    }
} 