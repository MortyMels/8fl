@extends('layouts.app')

@section('title', 'Мои формы')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Мои формы</h1>
        <a href="{{ route('forms.create') }}" 
           class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Создать форму
        </a>
    </div>

    @if($forms->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
            <svg class="mx-auto h-16 w-16 text-blue-500 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Нет форм</h3>
            <p class="mt-2 text-gray-500">Создайте свою первую форму, чтобы начать работу.</p>
            <div class="mt-8">
                <a href="{{ route('forms.create') }}" 
                   class="inline-flex items-center px-6 py-3 text-sm font-medium rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Создать форму
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($forms as $form)
                <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md hover:border-gray-300 transition-all duration-200 flex flex-col relative">
                    <!-- Кнопка удаления -->
                    <form action="{{ route('forms.destroy', $form) }}" method="POST" class="absolute top-0 right-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Вы уверены, что хотите удалить эту форму?')"
                                class="h-[40px] w-[40px] inline-flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors duration-200 border-l border-b border-gray-200 rounded-tr-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>

                    <div class="p-5 flex-grow">
                        <!-- Заголовок и статус -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 pr-4">
                                <h2 class="text-lg font-semibold text-gray-900 break-words group-hover:text-blue-600 transition-colors duration-200">
                                    {{ $form->name }}
                                </h2>
                                @if($form->description)
                                    <p class="mt-1.5 text-sm text-gray-500 line-clamp-2">
                                        {{ $form->description }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-col items-end mr-8">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $form->is_public ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                    {{ $form->is_public ? 'Публичная' : 'Приватная' }}
                                </span>
                            </div>
                        </div>

                        <!-- Дата создания -->
                        <div class="text-sm text-gray-500">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $form->created_at->format('d.m.Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Кнопки действий -->
                    <div class="grid grid-cols-3 divide-x divide-gray-200 border-t border-gray-200">
                        <a href="{{ route('forms.show', $form) }}" 
                           class="inline-flex items-center justify-center py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Заполнить
                        </a>
                        
                        <a href="{{ route('forms.submissions', $form) }}"
                           class="inline-flex items-center justify-center py-2.5 text-sm font-medium text-emerald-600 hover:bg-emerald-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Результаты
                        </a>

                        <a href="{{ route('forms.fields.index', $form) }}"
                           class="inline-flex items-center justify-center py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Изменить
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $forms->links() }}
        </div>
    @endif
</div>
@endsection 