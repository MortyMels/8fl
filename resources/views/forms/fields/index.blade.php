@extends('layouts.app')

@section('title', 'Редактирование формы')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Верхняя панель -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold text-gray-900">Редактирование формы</h1>
            <div class="flex items-center space-x-3">
                <a href="{{ route('forms.show', $form) }}" 
                   class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Просмотр формы
                </a>
                <a href="{{ route('forms.index') }}" 
                   class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    К списку форм
                </a>
            </div>
        </div>
    </div>

    <!-- Основные настройки формы -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Основные настройки формы</h2>
            <form action="{{ route('forms.update', $form) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Название формы
                        </label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               value="{{ old('name', $form->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Описание
                        </label>
                        <textarea id="description"
                               name="description" 
                               rows="2"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $form->description) }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_public" 
                               value="1"
                               @checked(old('is_public', $form->is_public))
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">
                            Публичная форма
                        </span>
                    </label>

                    <button type="submit" 
                            class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Параметры поля (слева) -->
        <div class="lg:col-span-4">
            <!-- Кнопка добавления поля -->
            <div class="mb-4">
                <button type="button" 
                        onclick="resetForm()"
                        class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Добавить поле
                </button>
            </div>

            <!-- Форма параметров поля -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6" id="formTitle">Параметры поля</h2>
                    
                    <form id="fieldForm" action="{{ route('forms.fields.store', $form) }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="field_id" value="">

                        <div>
                            <label for="label" class="form-label required">Название поля</label>
                            <input type="text" 
                                   id="label"
                                   name="label" 
                                   value="{{ old('label') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                   required>
                        </div>

                        <div>
                            <label for="description" class="form-label">Описание поля</label>
                            <textarea id="description"
                                      name="description" 
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                      rows="3">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="type" class="form-label required">Тип поля</label>
                            <select id="type" 
                                    name="type"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                    required>
                                <option value="">Выберите тип</option>
                                @foreach(App\Models\FormField::getTypes() as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type') == $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Контейнер для вариантов ответа -->
                        <div id="optionsContainer" class="hidden space-y-4">
                            <div>
                                <label class="form-label">Использовать существующий список</label>
                                <select id="dictionary_id" 
                                        name="dictionary_id"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                    <option value="">Выберите список или введите варианты вручную</option>
                                    @foreach($dictionaries as $dictionary)
                                        <option value="{{ $dictionary->id }}">{{ $dictionary->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label required">Варианты ответа</label>
                                <div id="optionsList" class="space-y-3">
                                    <div class="flex items-center gap-2">
                                        <input type="text" 
                                               name="options[]" 
                                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                               required>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="addOption()" 
                                        class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Добавить вариант
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="required" 
                                       value="1"
                                       @checked(old('required'))
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-base text-gray-700">Обязательное поле</span>
                            </label>
                        </div>

                        <button type="submit" 
                                id="submitButton"
                                class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Добавить поле
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Список полей (справа) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($fields->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        <p class="text-lg">В форме пока нет полей</p>
                        <p class="mt-1 text-sm">Добавьте первое поле, используя форму слева</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($fields as $field)
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $field->label }}
                                            @if($field->required)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ App\Models\FormField::getTypes()[$field->type] }}
                                            @if($field->description)
                                                <br>{{ $field->description }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <button type="button" 
                                                onclick='editField(@json($field))'
                                                class="text-blue-600 hover:text-blue-700">
                                            Редактировать
                                        </button>
                                        <form action="{{ route('forms.fields.destroy', [$form, $field]) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Вы уверены?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-700">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($field->options)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @if($field->dictionary_id)
                                            @foreach($field->dictionary->items()->orderBy('value')->pluck('value') as $option)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                    @if(in_array($option, $field->excluded_options ?? []))
                                                        bg-red-100 text-red-800
                                                    @else
                                                        bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $option }}
                                                </span>
                                            @endforeach
                                        @else
                                            @foreach($field->options as $option)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                    {{ $option }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
<script>
function addOption() {
    const container = document.getElementById('optionsList');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <input type="text" 
               name="options[]" 
               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
               required>
        <button type="button" 
                onclick="this.parentElement.remove()" 
                class="text-red-600 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

document.getElementById('type').addEventListener('change', function() {
    const optionsContainer = document.getElementById('optionsContainer');
    const hasOptions = ['select', 'checkbox', 'radio'].includes(this.value);
    
    optionsContainer.style.display = hasOptions ? 'block' : 'none';

    const optionsInputs = document.querySelectorAll('input[name="options[]"]');
    optionsInputs.forEach(input => {
        input.required = false;
    });

    if (hasOptions) {
        optionsInputs[0].required = true;
    }

    if (!hasOptions) {
        optionsInputs.forEach(input => {
            input.value = '';
        });
    }
});

// Обработчик выбора списка
document.addEventListener('DOMContentLoaded', function() {
    const dictionarySelect = document.getElementById('dictionary_id');
    
    dictionarySelect.addEventListener('change', async function() {
        const optionsList = document.getElementById('optionsList');
        const addButton = document.querySelector('button[onclick*="addOption"]');
        
        if (!this.value) {
            resetOptionsToManual(optionsList, addButton);
            return;
        }

        try {
            const response = await fetch(`/api/dictionaries/${this.value}/items`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const values = await response.json();
            
            if (!Array.isArray(values) || values.length === 0) {
                alert('Список пуст или не найден');
                this.value = '';
                resetOptionsToManual(optionsList, addButton);
                return;
            }

            optionsList.innerHTML = '';
            
            values.forEach(value => {
                const div = document.createElement('div');
                div.className = 'option-item flex items-center gap-2 mb-2';
                div.innerHTML = `
                    <input type="text" 
                           name="options[]" 
                           value="${value}"
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                           readonly>
                    <input type="hidden" 
                           name="excluded_options[]" 
                           value="${value}" 
                           disabled>  <!-- По умолчанию все опции видимые (disabled=true) -->
                    <div class="option-controls flex items-center">
                        <button type="button" 
                                onclick="toggleOptionVisibility(this)"
                                class="text-gray-400 hover:text-gray-600"
                                title="Показать/скрыть вариант">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                `;
                optionsList.appendChild(div);
            });

            if (addButton) addButton.style.display = 'none';

        } catch (error) {
            console.error('Error:', error);
            alert('Ошибка при загрузке значений списка: ' + error.message);
            this.value = '';
            resetOptionsToManual(optionsList, addButton);
        }
    });
});

function resetOptionsToManual(optionsList, addButton) {
    optionsList.innerHTML = `
        <div class="flex items-center gap-2">
            <input type="text" 
                   name="options[]" 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                   required>
        </div>
    `;
    if (addButton) addButton.style.display = 'inline-flex';
}

function toggleOptionVisibility(button) {
    const optionItem = button.closest('.option-item');
    const hiddenInput = optionItem.querySelector('input[type="hidden"]');
    const textInput = optionItem.querySelector('input[type="text"]');
    const isExcluded = !hiddenInput.disabled;

    if (!isExcluded) {
        // Скрываем опцию (добавляем в исключенные)
        hiddenInput.disabled = false;
        textInput.classList.add('line-through', 'text-gray-400');
        button.classList.remove('text-gray-400');
        button.classList.add('text-red-400');
        button.querySelector('svg').innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
    } else {
        // Показываем опцию (убираем из исключенных)
        hiddenInput.disabled = true;
        textInput.classList.remove('line-through', 'text-gray-400');
        button.classList.remove('text-red-400');
        button.classList.add('text-gray-400');
        button.querySelector('svg').innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }

    // Проверяем, есть ли хоть одна исключенная опция
    const hasExcludedOptions = Array.from(document.querySelectorAll('input[name="excluded_options[]"]'))
        .some(input => !input.disabled);

    // Если нет исключенных опций, добавляем скрытое поле с пустым значением
    const form = button.closest('form');
    let nullField = form.querySelector('input[name="excluded_options"]');
    if (!hasExcludedOptions) {
        if (!nullField) {
            nullField = document.createElement('input');
            nullField.type = 'hidden';
            nullField.name = 'excluded_options';
            nullField.value = '';
            form.appendChild(nullField);
        }
    } else if (nullField) {
        nullField.remove();
    }
}

function editField(field) {
    // Обновляем заголовок и кнопку
    document.getElementById('formTitle').textContent = 'Редактировать поле';
    document.getElementById('submitButton').textContent = 'Сохранить изменения';
    
    // Обновляем форму
    const form = document.getElementById('fieldForm');
    form.action = `/forms/${field.form_id}/fields/${field.id}`;
    form.querySelector('input[name="_method"]').value = 'PUT';
    form.querySelector('input[name="field_id"]').value = field.id;
    
    // Заполняем поля формы
    form.querySelector('input[name="label"]').value = field.label;
    form.querySelector('textarea[name="description"]').value = field.description || '';
    form.querySelector('select[name="type"]').value = field.type;
    form.querySelector('input[name="required"]').checked = field.required;
    
    // Обрабатываем опции если есть
    const typeSelect = form.querySelector('select[name="type"]');
    typeSelect.dispatchEvent(new Event('change')); // Триггерим событие для показа/скрытия опций
    
    if (field.dictionary_id) {
        form.querySelector('select[name="dictionary_id"]').value = field.dictionary_id;
        form.querySelector('select[name="dictionary_id"]').dispatchEvent(new Event('change'));
        
        // После загрузки опций из справочника отмечаем исключенные
        setTimeout(() => {
            const excludedOptions = field.excluded_options || [];
            document.querySelectorAll('.option-item').forEach(item => {
                const value = item.querySelector('input[type="text"]').value;
                if (excludedOptions.includes(value)) {
                    const hiddenInput = item.querySelector('input[type="hidden"]');
                    const textInput = item.querySelector('input[type="text"]');
                    const button = item.querySelector('button');
                    
                    // Явно устанавливаем состояние для исключенной опции
                    hiddenInput.disabled = false;  // Включаем поле для исключенных опций
                    textInput.classList.add('line-through', 'text-gray-400');
                    button.classList.remove('text-gray-400');
                    button.classList.add('text-red-400');
                    button.querySelector('svg').innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    `;
                }
            });
        }, 500);
    } else if (field.options && field.options.length) {
        const optionsList = document.getElementById('optionsList');
        optionsList.innerHTML = '';
        field.options.forEach(option => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2';
            div.innerHTML = `
                <input type="text" 
                       name="options[]" 
                       value="${option}"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       required>
                <button type="button" 
                        onclick="this.parentElement.remove()" 
                        class="text-red-600 hover:text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            optionsList.appendChild(div);
        });
    }
    
    // Прокручиваем к форме
    form.scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    // Сбрасываем заголовок и кнопку
    document.getElementById('formTitle').textContent = 'Добавить новое поле';
    document.getElementById('submitButton').textContent = 'Добавить поле';
    
    // Сбрасываем форму
    const form = document.getElementById('fieldForm');
    form.action = "{{ route('forms.fields.store', $form) }}";
    form.querySelector('input[name="_method"]').value = 'POST';
    form.querySelector('input[name="field_id"]').value = '';
    form.reset();
    
    // Очищаем опции
    const optionsList = document.getElementById('optionsList');
    if (optionsList) {
        optionsList.innerHTML = `
            <div class="flex items-center gap-2">
                <input type="text" 
                       name="options[]" 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                       required>
            </div>
        `;
    }
    
    // Скрываем контейнер опций
    const optionsContainer = document.getElementById('optionsContainer');
    if (optionsContainer) {
        optionsContainer.style.display = 'none';
    }
}
</script>

<style>
.form-label {
    @apply block text-sm font-medium text-gray-700 mb-2;
}
.form-label.required:after {
    content: "*";
    @apply text-red-500 ml-1;
}
[x-cloak] { 
    display: none !important; 
}
</style>
@endpush
@endsection 