<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты формы "{{ $form->name }}"</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Результаты формы</h1>
                <p class="text-gray-600 mt-2">{{ $form->name }}</p>
            </div>
            <div class="space-x-4">
                <a href="{{ route('forms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    К списку форм
                </a>
                <a href="{{ route('forms.fields.index', $form) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Управление полями
                </a>
                <a href="{{ route('forms.show', $form) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Заполнить форму
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            @if($submissions->isEmpty())
                <div class="text-center text-gray-500 py-8">
                    Пока нет отправленных форм
                </div>
            @else
                <!-- Форма фильтрации -->
                <form action="{{ route('forms.submissions', $form) }}" method="GET" class="mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h3 class="text-lg font-medium text-gray-700 mb-3">Фильтры</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($fields as $fieldName => $fieldLabel)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $fieldLabel }}
                                    </label>
                                    <input type="text" 
                                           name="filter_{{ $fieldName }}"
                                           value="{{ request()->get('filter_' . $fieldName) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="submit" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">
                                Применить фильтры
                            </button>
                            <a href="{{ route('forms.submissions', $form) }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 text-sm">
                                Сбросить
                            </a>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Данные формы</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата отправки</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($submissions as $submission)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $submission->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            @foreach($fields as $fieldName => $fieldLabel)
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-700">
                                                        {{ $fieldLabel }}:
                                                    </span>
                                                    <span class="text-gray-900">
                                                        @php
                                                            $value = $submission->data[$fieldName] ?? null;
                                                        @endphp
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $submission->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html> 