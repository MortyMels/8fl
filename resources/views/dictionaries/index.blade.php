<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Справочники</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Справочники</h1>
            <div class="space-x-4">
                <a href="{{ route('dictionaries.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Создать справочник
                </a>
                
                <!-- Форма для импорта -->
                <form action="{{ route('dictionaries.import') }}" 
                      method="POST" 
                      enctype="multipart/form-data" 
                      class="inline-flex items-center">
                    @csrf
                    <input type="file" 
                           name="file" 
                           accept=".json"
                           required
                           class="hidden"
                           id="importFile"
                           onchange="this.form.submit()">
                    <label for="importFile" 
                           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 cursor-pointer">
                        Импортировать справочник
                    </label>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md">
            @if($dictionaries->isEmpty())
                <p class="p-6 text-gray-500">Нет доступных справочников</p>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Название
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Описание
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Количество элементов
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Публичный
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dictionaries as $dictionary)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $dictionary->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $dictionary->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $dictionary->items_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $dictionary->is_public ? 'Да' : 'Нет' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @can('update', $dictionary)
                                        <a href="{{ route('dictionaries.edit', $dictionary) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-4">
                                            Редактировать
                                        </a>
                                        <a href="{{ route('dictionaries.export', $dictionary) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-4">
                                            Экспорт
                                        </a>
                                    @endcan
                                    @can('delete', $dictionary)
                                        <form action="{{ route('dictionaries.destroy', $dictionary) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Вы уверены?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                Удалить
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html> 