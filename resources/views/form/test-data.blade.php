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
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Генерация тестовых данных</h1>
                    <p class="text-gray-600 mt-2">{{ $form->name }}</p>
                </div>
                <div class="space-x-4">
                    <a href="{{ route('forms.submissions', $form) }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        К результатам
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('forms.test-data.generate', $form) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Количество записей для генерации
                            </label>
                            <input type="number" 
                                   name="count" 
                                   value="10"
                                   min="1"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Сгенерировать данные
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 