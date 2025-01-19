<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use App\Models\FormField;
use App\Jobs\GenerateFormTestData;

class FormTestDataController extends Controller
{
    public function show(Form $form)
    {
        return view('forms.test-data', compact('form'));
    }

    public function generate(Request $request, Form $form)
    {
        $request->validate([
            'count' => 'required|integer|min:1'
        ]);

        $count = $request->input('count', 10);
        
        GenerateFormTestData::dispatch($form, $count);

        return redirect()->route('forms.submissions', $form)
            ->with('success', "Запущена генерация {$count} тестовых записей. Это может занять некоторое время.");
    }

    private function generateFieldValue($field, $faker)
    {
        return match($field->type) {
            FormField::TYPE_TEXT => $faker->sentence(3),
            FormField::TYPE_TEXTAREA => $faker->paragraph,
            FormField::TYPE_NUMBER => $faker->numberBetween(1, 1000),
            FormField::TYPE_DATE => $faker->date(),
            FormField::TYPE_TIME => $faker->time('H:i'),
            FormField::TYPE_SELECT, FormField::TYPE_RADIO => $this->getRandomOption($field, $faker),
            FormField::TYPE_CHECKBOX => $this->getRandomOptions($field, $faker),
            default => null
        };
    }

    private function getRandomOption($field, $faker)
    {
        $options = $field->options;
        return !empty($options) ? $faker->randomElement($options) : null;
    }

    private function getRandomOptions($field, $faker)
    {
        $options = $field->options;
        if (empty($options)) return [];
        
        return $faker->randomElements(
            $options,
            $faker->numberBetween(1, min(3, count($options)))
        );
    }
} 