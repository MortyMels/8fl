<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование поля формы</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold">Редактирование поля формы</h1>
            <a href="{{ route('forms.fields.index', $form) }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Назад к списку
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('forms.fields.update', [$form, $field]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Метка поля</label>
                        <input type="text" name="label" value="{{ $field->label }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Имя поля</label>
                        <input type="text" name="name" value="{{ $field->name }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Тип поля</label>
                        <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach(['text' => 'Текстовое поле', 'textarea' => 'Многострочное поле', 'select' => 'Выпадающий список', 'checkbox' => 'Флажок', 'radio' => 'Радиокнопка', 'date' => 'Дата', 'time' => 'Время', 'number' => 'Число'] as $value => $label)
                                <option value="{{ $value }}" {{ $field->type === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Порядок сортировки</label>
                        <input type="number" name="sort_order" value="{{ $field->sort_order }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Обязательное поле</label>
                        <input type="checkbox" name="required" value="1" {{ $field->required ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Справочник (для select/radio/checkbox)</label>
                        <select name="dictionary_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Не использовать справочник</option>
                            @foreach($dictionaries as $dictionary)
                                <option value="{{ $dictionary->id }}" {{ $field->dictionary_id == $dictionary->id ? 'selected' : '' }}>
                                    {{ $dictionary->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Опции (для select/radio/checkbox, каждая опция с новой строки)</label>
                        <textarea name="options" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ is_array($field->options) ? implode("\n", $field->options) : '' }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 