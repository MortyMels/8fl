<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование справочника</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Редактирование справочника</h1>
                <a href="{{ route('dictionaries.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Назад к списку
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('dictionaries.update', $dictionary) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Название справочника</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $dictionary->name) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                            <textarea name="description" 
                                      rows="3" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $dictionary->description) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Значения (каждое с новой строки)</label>
                            <textarea name="values" 
                                      rows="10" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('values', $dictionary->items->pluck('value')->implode("\n")) }}</textarea>
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="checkbox" 
                                       name="is_public" 
                                       value="1"
                                       {{ old('is_public', $dictionary->is_public) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Публичный справочник</span>
                            </label>
                        </div>

                        <div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Сохранить изменения
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 