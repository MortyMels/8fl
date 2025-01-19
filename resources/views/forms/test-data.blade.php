<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Генерация тестовых данных</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Генерация тестовых данных для формы "{{ $form->name }}"</h1>
            <a href="{{ route('forms.submissions', $form) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Назад к результатам
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('forms.test-data.generate', $form) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="count" class="block text-sm font-medium text-gray-700">
                        Количество записей для генерации
                    </label>
                    <input type="number" 
                           name="count" 
                           id="count" 
                           value="10"
                           min="1"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Сгенерировать данные
                </button>
            </form>
        </div>
    </div>
</body>
</html> 