@extends('layouts.app')

@section('title', $form->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <h1 class="text-2xl font-bold text-gray-900">{{ $form->name }}</h1>
            @if($form->description)
                <p class="mt-2 text-gray-600">{{ $form->description }}</p>
            @endif

            <form action="{{ route('forms.submit', $form) }}" method="POST" class="mt-8 space-y-6">
                @csrf

                @foreach($fields as $field)
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ $field->label }}
                            @if($field->required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        
                        @if($field->description)
                            <p class="text-sm text-gray-500">{{ $field->description }}</p>
                        @endif

                        @switch($field->type)
                            @case('text')
                                <input type="text" 
                                       name="fields[{{ $field->id }}]"
                                       value="{{ old("fields.{$field->id}") }}"
                                       class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                       @if($field->required) required @endif>
                                @break

                            @case('textarea')
                                <textarea name="fields[{{ $field->id }}]"
                                          rows="3"
                                          class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                          @if($field->required) required @endif>{{ old("fields.{$field->id}") }}</textarea>
                                @break

                            @case('number')
                                <input type="number" 
                                       name="fields[{{ $field->id }}]"
                                       value="{{ old("fields.{$field->id}") }}"
                                       class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                       @if($field->required) required @endif>
                                @break

                            @case('date')
                                <input type="date" 
                                       name="fields[{{ $field->id }}]"
                                       value="{{ old("fields.{$field->id}") }}"
                                       class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                       @if($field->required) required @endif>
                                @break

                            @case('select')
                                <select name="fields[{{ $field->id }}]"
                                        class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                        @if($field->required) required @endif>
                                    <option value="">Выберите вариант</option>
                                    @foreach($field->options as $option)
                                        <option value="{{ $option }}" @selected(old("fields.{$field->id}") == $option)>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                @break

                            @case('checkbox')
                                <div class="mt-2 space-y-2">
                                    @foreach($field->options as $option)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="fields[{{ $field->id }}][]" 
                                                   value="{{ $option }}"
                                                   @checked(is_array(old("fields.{$field->id}")) && in_array($option, old("fields.{$field->id}")))
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-2">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('radio')
                                <div class="mt-2 space-y-2">
                                    @foreach($field->options as $option)
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="fields[{{ $field->id }}]" 
                                                   value="{{ $option }}"
                                                   @checked(old("fields.{$field->id}") == $option)
                                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                   @if($field->required) required @endif>
                                            <span class="ml-2">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break
                        @endswitch

                        @error("fields.{$field->id}")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="pt-4">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Отправить
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 