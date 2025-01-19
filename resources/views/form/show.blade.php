<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">{{ $form->name }}</h1>
                    @if($form->description)
                        <p class="text-gray-600 mt-2">{{ $form->description }}</p>
                    @endif
                </div>
                <div class="space-x-4">
                    <a href="{{ route('forms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        К списку форм
                    </a>
                    <a href="{{ route('forms.fields.index', $form) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Управление полями
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('forms.submit', $form) }}" method="POST">
                    @csrf
                    
                    @foreach($fields as $field)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $field->label }}
                                @if($field->required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @switch($field->type)
                                @case('text')
                                    <input type="text" 
                                           name="{{ $field->name }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           {{ $field->required ? 'required' : '' }}>
                                    @break

                                @case('number')
                                    <input type="number" 
                                           name="{{ $field->name }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           step="any"
                                           {{ $field->required ? 'required' : '' }}>
                                    @break

                                @case('date')
                                    <input type="date" 
                                           name="{{ $field->name }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           {{ $field->required ? 'required' : '' }}>
                                    @break

                                @case('textarea')
                                    <textarea name="{{ $field->name }}" 
                                              rows="3" 
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              {{ $field->required ? 'required' : '' }}></textarea>
                                    @break

                                @case('select')
                                    <select name="{{ $field->name }}" 
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            {{ $field->required ? 'required' : '' }}>
                                        <option value="">Выберите опцию</option>
                                        @if($field->dictionary_id)
                                            @foreach($field->dictionary->values as $value)
                                                <option value="{{ $value->value }}">{{ $value->value }}</option>
                                            @endforeach
                                        @else
                                            @foreach($field->options ?? [] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @break

                                @case('radio')
                                    <div class="space-y-2">
                                        @if($field->dictionary_id)
                                            @foreach($field->dictionary->values as $value)
                                                <div class="flex items-center">
                                                    <input type="radio" 
                                                           name="{{ $field->name }}" 
                                                           value="{{ $value->value }}"
                                                           class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                           {{ $field->required ? 'required' : '' }}>
                                                    <label class="ml-2">{{ $value->value }}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach($field->options ?? [] as $option)
                                                <div class="flex items-center">
                                                    <input type="radio" 
                                                           name="{{ $field->name }}" 
                                                           value="{{ $option }}"
                                                           class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                           {{ $field->required ? 'required' : '' }}>
                                                    <label class="ml-2">{{ $option }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    @break

                                @case('checkbox')
                                    <div class="space-y-2">
                                        @if($field->dictionary_id)
                                            @foreach($field->dictionary->values as $value)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="{{ $field->name }}[]" 
                                                           value="{{ $value->value }}"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <label class="ml-2">{{ $value->value }}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach($field->options ?? [] as $option)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="{{ $field->name }}[]" 
                                                           value="{{ $option }}"
                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <label class="ml-2">{{ $option }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    @break

                                @case('time')
                                    <input type="time" 
                                           name="{{ $field->name }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           {{ $field->required ? 'required' : '' }}>
                                    @break
                            @endswitch
                        </div>
                    @endforeach

                    <div class="mt-6">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Отправить форму
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 