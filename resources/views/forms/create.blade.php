@extends('layouts.app')

@section('title', 'Создание формы')

@section('content')
<div class="card">
    <div class="card-header flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-900">Создание формы</h1>
        <a href="{{ route('forms.index') }}" class="btn btn-secondary">
            Назад к списку
        </a>
    </div>

    <div class="card-body">
        <form action="{{ route('forms.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="form-label">Название формы</label>
                <input type="text" 
                       id="name"
                       name="name" 
                       value="{{ old('name') }}"
                       class="form-input"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="form-label">Описание</label>
                <textarea id="description"
                          name="description" 
                          rows="3" 
                          class="form-input">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" 
                       id="is_public"
                       name="is_public" 
                       value="1"
                       @checked(old('is_public'))
                       class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300">
                <label for="is_public" class="ml-2 text-sm text-gray-700">
                    Публичная форма
                </label>
                @error('is_public')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('forms.index') }}" class="btn btn-secondary">
                    Отмена
                </a>
                <button type="submit" class="btn btn-primary">
                    Создать форму
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 