<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование поля</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Редактирование поля формы "{{ $form->name }}"</h1>
            <a href="{{ route('forms.fields.index', $form) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Назад к полям
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('forms.fields.update', [$form, $field]) }}" method="POST">
                @csrf
                @method('PUT')
                
                @include('forms.fields.form')
                
                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 