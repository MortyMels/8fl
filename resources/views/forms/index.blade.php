@extends('layouts.app')

@section('title', 'Мои формы')

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-900">Мои формы</h1>
        <a href="{{ route('forms.create') }}" class="btn btn-primary">
            Создать форму
        </a>
    </div>

    @if($forms->isEmpty())
        <div class="card-body text-center text-gray-500">
            <p class="text-lg">У вас пока нет созданных форм</p>
            <p class="mt-2 text-sm">Нажмите "Создать форму" чтобы начать</p>
        </div>
    @else
        <div class="overflow-x-auto">
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
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($forms as $form)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $form->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($form->description, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-4">
                                    <a href="{{ route('forms.show', $form) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Просмотр
                                    </a>
                                    <a href="{{ route('forms.edit', $form) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Редактировать
                                    </a>
                                    <form action="{{ route('forms.destroy', $form) }}" 
                                          method="POST" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Вы уверены?')">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 