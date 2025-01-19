<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\Dictionary;
use Illuminate\Http\Request;

class FormFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Form $form)
    {
        $this->authorize('manageFields', $form);
        
        $fields = $form->fields()->orderBy('sort_order')->get();
        
        // Получаем списки текущего пользователя и публичные
        $dictionaries = Dictionary::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhere('is_public', true);
        })->orderBy('name')->get();

        return view('forms.fields.index', compact('form', 'fields', 'dictionaries'));
    }

    protected function validateField(Request $request): array
    {
        return $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|string|in:text,textarea,number,date,time,select,checkbox,radio',
            'required' => 'boolean',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'dictionary_id' => 'nullable|exists:dictionaries,id',
            'excluded_options' => 'nullable|array',
            'excluded_options.*' => 'string'
        ]);
    }

    public function store(Request $request, Form $form)
    {
        $this->authorize('manageFields', $form);
        $validated = $this->validateField($request);
        
        // Если выбран справочник, получаем его значения за исключением исключенных
        if (!empty($validated['dictionary_id'])) {
            $dictionary = Dictionary::findOrFail($validated['dictionary_id']);
            
            // Получаем массив значений, которые НЕ были отмечены как исключенные
            $excludedOptions = array_filter($request->input('excluded_options', []), function($item) {
                return $item !== null && !empty($item);
            });
            
            $values = $dictionary->items()
                ->whereNotIn('value', $excludedOptions)
                ->orderBy('value')
                ->pluck('value')
                ->toArray();
                
            $validated['options'] = $values;
        }

        $form->fields()->create($validated);

        return redirect()
            ->route('forms.fields.index', $form)
            ->with('success', 'Поле успешно добавлено');
    }

    public function update(Request $request, Form $form, FormField $field)
    {
        $this->authorize('manageFields', $form);

        $validated = $this->validateField($request);

        // Если выбран справочник, получаем его значения за исключением исключенных
        if (!empty($validated['dictionary_id'])) {
            $dictionary = Dictionary::findOrFail($validated['dictionary_id']);
            
            // Получаем массив значений, которые НЕ были отмечены как исключенные
            $excludedOptions = array_filter($request->input('excluded_options', []), function($item) {
                return $item !== null && !empty($item);
            });
            
            $values = $dictionary->items()
                ->whereNotIn('value', $excludedOptions)
                ->orderBy('value')
                ->pluck('value')
                ->toArray();
                
            $validated['options'] = $values;
        }

        // Устанавливаем значения по умолчанию
        $validated['required'] = $validated['required'] ?? false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $field->update($validated);

        return redirect()
            ->route('forms.fields.index', $form)
            ->with('success', 'Поле успешно обновлено');
    }

    public function destroy(Form $form, FormField $field)
    {
        $this->authorize('manageFields', $form);
        
        $field->delete();

        return redirect()
            ->route('forms.fields.index', $form)
            ->with('success', 'Поле успешно удалено');
    }
} 