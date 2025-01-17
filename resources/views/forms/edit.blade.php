<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование формы</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold">Редактирование формы</h1>
                <a href="{{ route('forms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Назад к списку
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('forms.update', $form) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Название формы</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ $form->name }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Описание</label>
                            <textarea name="description" 
                                      rows="3" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $form->description }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <input type="checkbox" 
                                       name="is_public" 
                                       value="1" 
                                       {{ $form->is_public ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                Публичная форма
                            </label>
                            <p class="text-sm text-gray-500">Публичные формы доступны всем пользователям</p>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Предоставить доступ пользователям</label>
                            <div class="space-y-2">
                                @foreach($users as $user)
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" 
                                                   name="shared_users[]" 
                                                   value="{{ $user->id }}"
                                                   {{ in_array($user->id, $sharedUsers) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="ml-2">{{ $user->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Сохранить изменения
                            </button>
                            
                            <a href="{{ route('forms.fields.index', $form) }}" 
                               class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Управление полями
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 