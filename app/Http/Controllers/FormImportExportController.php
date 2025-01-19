<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormImportExportController extends Controller
{
    public function export(Form $form)
    {
        $data = [
            'form' => [
                'name' => $form->name,
                'description' => $form->description,
                'is_public' => $form->is_public,
            ],
            'fields' => $form->fields->map(function ($field) {
                return [
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'required' => $field->required,
                    'options' => $field->options,
                    'sort_order' => $field->sort_order,
                ];
            })->toArray()
        ];

        $filename = slug($form->name) . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename=' . $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048'
        ]);

        try {
            $content = file_get_contents($request->file('file')->path());
            $data = json_decode($content, true);

            if (!isset($data['form']) || !isset($data['fields'])) {
                throw new \Exception('Неверный формат файла');
            }

            DB::beginTransaction();

            // Создаем форму
            $form = Form::create([
                'name' => $data['form']['name'] . ' (Импорт)',
                'description' => $data['form']['description'],
                'is_public' => $data['form']['is_public'],
                'user_id' => auth()->id()
            ]);

            // Создаем поля
            foreach ($data['fields'] as $fieldData) {
                $form->fields()->create($fieldData);
            }

            DB::commit();

            return redirect()->route('forms.index')
                ->with('success', 'Форма успешно импортирована');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ошибка при импорте формы: ' . $e->getMessage());
        }
    }
} 