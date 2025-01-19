@extends('layouts.app')

@section('title', 'Редактирование формы')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Верхняя панель -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold text-gray-900">Редактирование формы</h1>
            <div class="flex items-center space-x-3">
                <a href="{{ route('forms.fields.index', $form) }}" 
                   class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Управление полями
                </a>
                <a href="{{ route('forms.index') }}" 
                   class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Назад к списку
                </a>
            </div>
        </div>
    </div>

    <!-- Основная форма -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('forms.update', $form) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-8">
                <!-- Основные настройки -->
                <div class="space-y-6">
                    <div>
                        <div class="mb-4">
                            <label for="name" class="form-label required text-base">Название формы</label>
                        </div>
                        <input type="text" 
                               id="name"
                               name="name" 
                               value="{{ old('name', $form->name) }}"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="description" class="form-label text-base">Описание</label>
                        </div>
                        <textarea id="description"
                                  name="description" 
                                  rows="4" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">{{ old('description', $form->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Настройки доступа -->
                <div class="border-t border-gray-200 pt-8">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Настройки доступа</h3>
                        <p class="mt-1 text-sm text-gray-500">Определите, кто сможет просматривать и заполнять форму</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <label class="flex items-start cursor-pointer">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           id="is_public"
                                           name="is_public" 
                                           value="1"
                                           @checked(old('is_public', $form->is_public))
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                                <div class="ml-3">
                                    <span class="text-base font-medium text-gray-700">Публичная форма</span>
                                    <p class="text-sm text-gray-500">Форма будет доступна всем пользователям для просмотра и заполнения</p>
                                </div>
                            </label>
                        </div>

                        @if($users->isNotEmpty())
                            <div>
                                <div class="mb-4">
                                    <label class="form-label text-base">Предоставить доступ пользователям</label>
                                </div>
                                <div class="grid gap-3">
                                    @foreach($users as $user)
                                        <label class="flex items-center p-4 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                            <input type="checkbox" 
                                                   name="shared_users[]" 
                                                   value="{{ $user->id }}"
                                                   @checked(in_array($user->id, $sharedUsers))
                                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-3 text-base font-medium text-gray-700">{{ $user->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('forms.index') }}" 
                       class="inline-flex justify-center items-center px-6 py-2.5 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Отмена
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center items-center px-6 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Сохранить изменения
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label.required:after {
        content: "*";
        color: #ef4444;
        margin-left: 0.25rem;
    }
</style>
@endsection 