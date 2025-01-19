<div class="space-y-4">
    <div>
        <label for="label" class="block text-sm font-medium text-gray-700">Название поля</label>
        <input type="text" 
               name="label" 
               id="label" 
               value="{{ old('label', $field->label ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('label')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Тип поля</label>
        <div class="flex flex-wrap gap-2">
            <input type="hidden" name="type" id="selectedType" value="{{ old('type', $field->type ?? 'text') }}">
            
            <button type="button" 
                    onclick="selectType('text')" 
                    id="type_text"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Текст
            </button>
            
            <button type="button" 
                    onclick="selectType('textarea')" 
                    id="type_textarea"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Многострочный текст
            </button>
            
            <button type="button" 
                    onclick="selectType('number')" 
                    id="type_number"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Число
            </button>
            
            <button type="button" 
                    onclick="selectType('date')" 
                    id="type_date"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Дата
            </button>
            
            <button type="button" 
                    onclick="selectType('time')" 
                    id="type_time"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Время
            </button>
            
            <button type="button" 
                    onclick="selectType('select')" 
                    id="type_select"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Выпадающий список
            </button>
            
            <button type="button" 
                    onclick="selectType('checkbox')" 
                    id="type_checkbox"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Множественный выбор
            </button>
            
            <button type="button" 
                    onclick="selectType('radio')" 
                    id="type_radio"
                    class="px-3 py-2 rounded-md text-sm font-medium border focus:outline-none">
                Одиночный выбор
            </button>
        </div>
    </div>

    <div id="optionsBlock" style="display: none;">
        <div class="mb-4">
            <label for="dictionary_id" class="block text-sm font-medium text-gray-700">Справочник</label>
            <select name="dictionary_id" 
                    id="dictionary_id" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    onchange="toggleCustomOptions(this.value)">
                <option value="">Не использовать справочник</option>
                @foreach($dictionaries as $dictionary)
                    <option value="{{ $dictionary->id }}" 
                            {{ old('dictionary_id', $field->dictionary_id ?? '') == $dictionary->id ? 'selected' : '' }}>
                        {{ $dictionary->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="customOptionsBlock">
            <label for="options" class="block text-sm font-medium text-gray-700">Варианты (каждый с новой строки)</label>
            <textarea name="options" 
                      id="options" 
                      rows="4"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('options', is_array($field->options ?? null) ? implode("\n", $field->options) : '') }}</textarea>
        </div>
    </div>

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" 
                   name="required" 
                   value="1" 
                   {{ old('required', $field->required ?? false) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ml-2 text-sm text-gray-600">Обязательное поле</span>
        </label>
    </div>
</div>

<script>
function selectType(type) {
    // Обновляем скрытое поле
    document.getElementById('selectedType').value = type;
    
    // Обновляем стили кнопок
    document.querySelectorAll('[id^="type_"]').forEach(button => {
        if (button.id === 'type_' + type) {
            button.classList.add('bg-indigo-600', 'text-white');
            button.classList.remove('bg-white', 'text-gray-700');
        } else {
            button.classList.remove('bg-indigo-600', 'text-white');
            button.classList.add('bg-white', 'text-gray-700');
        }
    });
    
    // Показываем/скрываем блок опций
    toggleOptions(type);
}

function toggleOptions(type) {
    const optionsBlock = document.getElementById('optionsBlock');
    optionsBlock.style.display = ['select', 'checkbox', 'radio'].includes(type) ? 'block' : 'none';
}

function toggleCustomOptions(dictionaryId) {
    const customOptionsBlock = document.getElementById('customOptionsBlock');
    customOptionsBlock.style.display = dictionaryId ? 'none' : 'block';
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    const selectedType = document.getElementById('selectedType').value;
    selectType(selectedType);
    toggleCustomOptions(document.getElementById('dictionary_id').value);
});
</script> 