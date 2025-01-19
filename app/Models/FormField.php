<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    protected $fillable = [
        'label',
        'type',
        'required',
        'options',
        'dictionary_id',
        'sort_order',
        'description',
        'excluded_options'
    ];

    protected $casts = [
        'required' => 'boolean',
        'options' => 'array',
        'excluded_options' => 'array'
    ];

    // Типы полей
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_NUMBER = 'number';
    public const TYPE_DATE = 'date';
    public const TYPE_TIME = 'time';
    public const TYPE_SELECT = 'select';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_RADIO = 'radio';

    public static function getTypes(): array
    {
        return [
            'text' => 'Текстовое поле',
            'textarea' => 'Многострочное поле',
            'number' => 'Числовое поле',
            'date' => 'Дата',
            'select' => 'Выпадающий список',
            'checkbox' => 'Множественный выбор',
            'radio' => 'Одиночный выбор'
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function hasOptions(): bool
    {
        return in_array($this->type, [
            self::TYPE_SELECT,
            self::TYPE_CHECKBOX,
            self::TYPE_RADIO
        ]);
    }

    public function getOptionsAttribute($value)
    {
        // Если есть dictionary_id и справочник существует
        if ($this->dictionary_id && $this->dictionary) {
            try {
                // Получаем исключенные опции
                $excludedOptions = $this->excluded_options ?? [];
                
                return $this->dictionary->items()
                    ->whereNotIn('value', $excludedOptions) // Исключаем скрытые опции
                    ->orderBy('value')
                    ->pluck('value')
                    ->toArray();
            } catch (\Exception $e) {
                \Log::error('Error getting dictionary items: ' . $e->getMessage());
                return [];
            }
        }
        
        // Если нет справочника или он не найден, возвращаем значение из поля options
        return json_decode($value, true) ?? [];
    }

    public function setOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['options'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['options'] = null;
        }
    }

    public function dictionary()
    {
        return $this->belongsTo(Dictionary::class);
    }

    public function setExcludedOptionsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['excluded_options'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['excluded_options'] = null;
        }
    }
} 