@extends('layouts.app')

@section('title', 'Управление полями формы')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Верхняя панель -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Управление полями формы</h1>
                <p class="mt-1 text-gray-500">{{ $form->name }}</p>
            </div>
            <div class="flex gap-3">
                <button type="button" 
                        onclick="resetForm()"
                        class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Добавить поле
                </button>
                <a href="{{ route('forms.edit', $form) }}" 
                   class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Назад к форме
                </a>
            </div>
        </div>
            </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Форма добавления/редактирования поля -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6" id="formTitle">Добавить новое поле</h2>
                    
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
                            @error('label')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="form-label">Описание поля</label>
                            <textarea id="description"
                                      name="description" 
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                                      rows="3">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Дополнительная информация о поле (необязательно)</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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

        <!-- Список полей -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @if($fields->isEmpty())
                    <div class="p-6 text-center text-gray-500">
                        <p class="text-lg">В форме пока нет полей</p>
                        <p class="mt-1 text-sm">Добавьте первое поле, нажав кнопку "Добавить поле"</p>
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
                                            {{-- Для опций из справочника показываем все варианты, включая исключенные --}}
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
                                            {{-- Для обычных опций показываем как раньше --}}
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
               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
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
    
    // Показываем/скрываем контейнер с опциями
    optionsContainer.style.display = hasOptions ? 'block' : 'none';

    // Управляем обязательностью полей
    const optionsInputs = document.querySelectorAll('input[name="options[]"]');
    
    // Сбрасываем required для всех полей
    optionsInputs.forEach(input => {
        input.required = false;
    });

    // Устанавливаем required если нужны опции
    if (hasOptions) {
        optionsInputs[0].required = true;
    }

    // Очищаем значения если опции не нужны
    if (!hasOptions) {
        optionsInputs.forEach(input => {
            input.value = '';
        });
    }
});

function addOptionToField(container) {
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
    container.insertBefore(div, container.lastElementChild);
}

// Обработчик выбора списка
document.addEventListener('DOMContentLoaded', function() {
    const dictionarySelects = document.querySelectorAll('#dictionary_id, .dictionary-select');
    
    dictionarySelects.forEach(select => {
        select.addEventListener('change', async function() {
            const container = this.closest('.space-y-4, .space-y-2');
            const optionsList = container.querySelector('.options-list, #optionsList');
            const addButton = container.querySelector('button[onclick*="addOption"], button[onclick*="addOptionToField"]');
            
            if (!optionsList) {
                console.error('Не найден контейнер для опций');
                return;
            }

            if (!this.value) {
                resetOptionsToManual(optionsList, addButton);
                return;
            }

            try {
                const response = await fetch(`/api/dictionaries/${this.value}/items`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const values = await response.json();
                
                if (!Array.isArray(values) || values.length === 0) {
                    alert('Список пуст или не найден');
                    this.value = '';
                    resetOptionsToManual(optionsList, addButton);
                    return;
                }

                // Очищаем текущие опции
                optionsList.innerHTML = '';
                
                // Добавляем новые опции из списка
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
                               disabled>
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

                // Скрываем кнопку добавления
                if (addButton) addButton.style.display = 'none';

            } catch (error) {
                console.error('Error:', error);
                alert('Ошибка при загрузке значений списка: ' + error.message);
                this.value = '';
                resetOptionsToManual(optionsList, addButton);
            }
        });
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

// Функция для переключения видимости опции
function toggleOptionVisibility(button) {
    const optionItem = button.closest('.option-item');
    const hiddenInput = optionItem.querySelector('input[type="hidden"]');
    const textInput = optionItem.querySelector('input[type="text"]');
    const isVisible = hiddenInput.disabled;

    if (isVisible) {
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
}

document.querySelectorAll('input[name="options_source"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const dictionarySelect = document.getElementById('dictionarySelect');
        const manualOptions = document.getElementById('manualOptions');
        
        if (this.value === 'dictionary') {
            dictionarySelect.classList.remove('hidden');
            manualOptions.classList.add('hidden');
        } else {
            dictionarySelect.classList.add('hidden');
            manualOptions.classList.remove('hidden');
        }
    });
});

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
                    const button = item.querySelector('button');
                    toggleOptionVisibility(button);
                }
            });
        }, 500); // Даем время на загрузку опций
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
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection 