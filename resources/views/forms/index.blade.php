<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление формами</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Управление формами</h1>
                @guest
                    <p class="text-gray-600 mt-2">
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-900">Войдите</a>
                        или
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-900">зарегистрируйтесь</a>
                        чтобы создавать свои формы
                    </p>
                @endguest
            </div>
            <div class="space-x-4">
                @auth
                    <a href="{{ route('forms.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Создать форму
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Войти
                    </a>
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Регистрация
                    </a>
                @endauth
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            @if($forms->isEmpty())
                <div class="text-center text-gray-500 py-8">
                    Нет созданных форм
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($forms as $form)
                        <div class="border rounded-lg p-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $form->name }}</h3>
                            @if($form->description)
                                <p class="text-gray-600 mb-4">{{ $form->description }}</p>
                            @endif
                            <div class="space-y-2">
                                <a href="{{ route('forms.fields.index', $form) }}" 
                                   class="block text-indigo-600 hover:text-indigo-900">
                                    Управление полями
                                </a>
                                <a href="{{ route('forms.show', $form) }}" 
                                   class="block text-green-600 hover:text-green-900">
                                    Просмотр формы
                                </a>
                                <a href="{{ route('forms.submissions', $form) }}" 
                                   class="block text-blue-600 hover:text-blue-900">
                                    Результаты
                                </a>
                                <div class="flex space-x-4 mt-4">
                                    <a href="{{ route('forms.edit', $form) }}" 
                                       class="text-gray-600 hover:text-gray-900">
                                        Редактировать
                                    </a>
                                    <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 mb-2">
                                @if($form->user_id === auth()->id())
                                    Ваша форма
                                @else
                                    Владелец: {{ $form->user->name }}
                                @endif
                                
                                @if($form->is_public)
                                    <span class="ml-2 text-green-600">Публичная</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html> 