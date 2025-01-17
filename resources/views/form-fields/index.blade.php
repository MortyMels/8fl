<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление полями формы "{{ $form->name }}"</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Управление полями формы</h1>
                <p class="text-gray-600 mt-2">{{ $form->name }}</p>
            </div>
            <div class="space-x-4">
                <a href="{{ route('forms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    К списку форм
                </a>
                <a href="{{ route('forms.show', $form) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Просмотр формы
                </a>
                <a href="{{ route('forms.submissions', $form) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Результаты
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Форма создания поля -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Добавить новое поле</h2>
            <form action="{{ route('forms.fields.store', $form) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Метка поля</label>
                        <input type="text" name="label" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Имя поля</label>
                        <input type="text" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Тип поля</label>
                        <select name="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="text">Текстовое поле</option>
                            <option value="textarea">Многострочное поле</option>
                            <option value="select">Выпадающий список</option>
                            <option value="checkbox">Флажок</option>
                            <option value="radio">Радиокнопка</option>
                            <option value="date">Дата</option>
                            <option value="number">Число</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Порядок сортировки</label>
                        <input type="number" name="sort_order" value="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Обязательное поле</label>
                        <input type="checkbox" name="required" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Опции (для select/radio/checkbox, каждая опция с новой строки)</label>
                        <textarea name="options" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Добавить поле
                    </button>
                </div>
            </form>
        </div>

        <!-- Список существующих полей -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Существующие поля</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Метка</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тип</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Обязательное</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($fields as $field)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $field->label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $field->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $field->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $field->required ? 'Да' : 'Нет' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('forms.fields.edit', [$form, $field]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Редактировать</a>
                                    <form action="{{ route('forms.fields.destroy', [$form, $field]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html> 