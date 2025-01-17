<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function show(Form $form)
    {
        $fields = $form->fields()->orderBy('sort_order')->get();
        return view('form.show', compact('form', 'fields'));
    }

    public function submit(Request $request, Form $form)
    {
        try {
            $fields = $form->fields;
            
            // Собираем данные формы
            $formData = [];
            foreach ($fields as $field) {
                $value = $request->input($field->name);
                
                // Обработка значений в зависимости от типа
                switch ($field->type) {
                    case 'checkbox':
                        if (is_array($value)) {
                            $formData[$field->name] = $value;
                        }
                        break;
                        
                    case 'date':
                        if ($value) {
                            $formData[$field->name] = date('Y-m-d', strtotime($value));
                        }
                        break;
                        
                    case 'number':
                        if ($value !== null) {
                            $formData[$field->name] = is_numeric($value) ? floatval($value) : null;
                        }
                        break;
                        
                    default:
                        $formData[$field->name] = $value;
                }
            }
            
            // Валидация
            $validationRules = [];
            foreach ($fields as $field) {
                $rules = [];
                if ($field->required) {
                    $rules[] = 'required';
                } else {
                    $rules[] = 'nullable';
                }
                
                switch ($field->type) {
                    case 'number':
                        $rules[] = 'numeric';
                        break;
                    case 'date':
                        $rules[] = 'date';
                        break;
                }
                
                $validationRules[$field->name] = implode('|', $rules);
            }
            
            $validated = $request->validate($validationRules);
            
            // Сохраняем результат
            $form->submissions()->create([
                'data' => $formData
            ]);

            return redirect()->back()->with('success', 'Форма успешно отправлена!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Произошла ошибка при отправке формы: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function submissions(Form $form)
    {
        $submissions = $form->submissions()->latest()->paginate(10);
        $fields = $form->fields()->pluck('label', 'name')->toArray();
        
        return view('form.submissions', compact('form', 'submissions', 'fields'));
    }
} 