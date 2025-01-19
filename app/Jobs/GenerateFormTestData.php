<?php

namespace App\Jobs;

use App\Models\Form;
use Faker\Factory as Faker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateFormTestData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $form;
    protected $count;
    public $timeout = 3600; // 1 час
    public $tries = 1;

    public function __construct(Form $form, int $count)
    {
        $this->form = $form;
        $this->count = $count;
    }

    public function handle()
    {
        $faker = Faker::create('ru_RU');
        $batchSize = 100; // Уменьшаем размер пакета
        $batches = ceil($this->count / $batchSize);

        for ($batch = 0; $batch < $batches; $batch++) {
            $records = [];
            $currentBatchSize = min($batchSize, $this->count - ($batch * $batchSize));

            for ($i = 0; $i < $currentBatchSize; $i++) {
                $data = [];
                foreach ($this->form->fields as $field) {
                    $data[$field->name] = $this->generateFieldValue($field, $faker);
                }
                $records[] = [
                    'form_id' => $this->form->id,
                    'data' => json_encode($data),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->form->submissions()->insert($records);
            
            // Добавляем небольшую задержку между пакетами
            if ($batch < $batches - 1) {
                usleep(100000); // 100ms пауза
            }
        }
    }

    private function generateFieldValue($field, $faker)
    {
        switch ($field->type) {
            case 'text':
                return $faker->sentence(3);
            case 'textarea':
                return $faker->paragraph;
            case 'number':
                return $faker->numberBetween(1, 1000);
            case 'date':
                return $faker->date();
            case 'time':
                return $faker->time('H:i');
            case 'select':
            case 'radio':
                if ($field->dictionary_id) {
                    $values = $field->dictionary->values->pluck('value')->toArray();
                    return $faker->randomElement($values);
                }
                return $faker->randomElement($field->options ?? []);
            case 'checkbox':
                if ($field->dictionary_id) {
                    $values = $field->dictionary->values->pluck('value')->toArray();
                    return $faker->randomElements($values, $faker->numberBetween(1, count($values)));
                }
                return $faker->randomElements($field->options ?? [], $faker->numberBetween(1, count($field->options ?? [])));
            default:
                return '';
        }
    }
} 