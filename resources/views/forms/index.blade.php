@extends('layouts.app')

@section('title', 'Мои формы')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Мои формы</h1>
        <a href="{{ route('forms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-150 ease-in-out">
            Создать форму
        </a>
    </div>

    @if($forms->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <p class="text-lg text-gray-600">У вас пока нет созданных форм</p>
            <p class="mt-2 text-sm text-gray-500">Нажмите "Создать форму" чтобы начать</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($forms as $form)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $form->name }}</h2>
                        <p class="text-gray-600 mb-4 h-12 overflow-hidden">
                            {{ Str::limit($form->description, 100) }}
                        </p>
                        
                        <div class="border-t pt-4">
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('forms.show', $form) }}" 
                                   class="inline-flex justify-center items-center px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition duration-150 ease-in-out text-sm">
                                    <span>Заполнить</span>
                                </a>
                                
                                <a href="{{ route('forms.submissions', $form) }}" 
                                   class="inline-flex justify-center items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition duration-150 ease-in-out text-sm">
                                    <span>Результаты</span>
                                </a>
                                
                                <a href="{{ route('forms.edit', $form) }}" 
                                   class="inline-flex justify-center items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-md hover:bg-yellow-200 transition duration-150 ease-in-out text-sm">
                                    <span>Редактировать</span>
                                </a>
                                
                                <form action="{{ route('forms.destroy', $form) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Вы уверены, что хотите удалить эту форму?')"
                                            class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition duration-150 ease-in-out text-sm">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 