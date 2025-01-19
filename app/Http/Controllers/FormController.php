<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function show(Form $form)
    {
        // Получаем поля формы, отсортированные по sort_order
        $fields = $form->fields()
            ->orderBy('sort_order')
            ->get();

        return view('forms.show', compact('form', 'fields'));
    }

    public function submit(Request $request, Form $form)
    {
        // Получаем все поля формы
        $fields = $form->fields;
        
        // Формируем правила валидации
        $rules = [];
        foreach ($fields as $field) {
            $rule = ['nullable'];
            
            if ($field->required) {
                $rule = ['required'];
            }
            
            switch ($field->type) {
                case 'number':
                    $rule[] = 'numeric';
                    break;
                case 'date':
                    $rule[] = 'date';
                    break;
                case 'checkbox':
                    $rule = ['array'];
                    if ($field->required) {
                        $rule[] = 'min:1';
                    }
                    break;
            }
            
            $rules["fields.{$field->id}"] = $rule;
        }
        
        // Валидируем данные
        $validated = $request->validate($rules);
        
        // Сохраняем ответы
        $form->submissions()->create([
            'data' => $validated['fields']
        ]);
        
        return redirect()
            ->back()
            ->with('success', 'Форма успешно отправлена');
    }

    public function submissions(Form $form)
    {
        $submissions = $form->submissions()
            ->latest()
            ->paginate(20);

        return view('forms.submissions', compact('form', 'submissions'));
    }
} 