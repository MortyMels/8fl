<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты формы "{{ $form->name }}"</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold">Результаты формы</h1>
                <p class="text-gray-600 mt-2">{{ $form->name }}</p>
            </div>
            <div class="space-x-4">
                <a href="{{ route('forms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    К списку форм
                </a>
                <a href="{{ route('forms.fields.index', $form) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Управление полями
                </a>
                <a href="{{ route('forms.show', $form) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Заполнить форму
                </a>
                <a href="{{ route('forms.test-data', $form) }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                    Генерация тестовых данных
                </a>
                @if($submissions->isNotEmpty())
                    <form action="{{ route('forms.submissions.delete', $form) }}" 
                          method="POST" 
                          class="inline" 
                          onsubmit="return confirm('Вы уверены, что хотите удалить все результаты?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="all" value="1">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                            Очистить все результаты
                        </button>
                    </form>
                    <button type="button" 
                            onclick="deleteSelected()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        Удалить выбранные
                    </button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            @if($submissions->isEmpty())
                <div class="text-center text-gray-500 py-8">
                    Пока нет отправленных форм
                </div>
            @else
                <div class="mb-6">
                    <form id="filterForm" action="{{ route('forms.submissions', $form) }}" method="GET" class="bg-white p-4 rounded-lg shadow">
                        <div id="filterContainer" class="space-y-4">
                            @if(request()->has('filters'))
                                @foreach(request('filters') as $groupId => $filter)
                                    <div class="filter-group bg-gray-50 p-4 rounded" data-group="{{ $groupId }}">
                                        <div class="flex items-center gap-4">
                                            @if(!$loop->first)
                                                <select name="filters[{{ $groupId }}][logical_operator]" class="rounded-md border-gray-300">
                                                    <option value="and" {{ ($filter['logical_operator'] ?? 'and') === 'and' ? 'selected' : '' }}>И</option>
                                                    <option value="or" {{ ($filter['logical_operator'] ?? 'and') === 'or' ? 'selected' : '' }}>ИЛИ</option>
                                                </select>
                                            @endif
                                            
                                            <select name="filters[{{ $groupId }}][field]" 
                                                    onchange="updateOperators(this)" 
                                                    class="rounded-md border-gray-300">
                                                @foreach($fields as $field)
                                                    <option value="{{ $field->name }}" 
                                                            data-type="{{ $field->type }}"
                                                            data-options="{{ json_encode($field->options) }}"
                                                            {{ $filter['field'] === $field->name ? 'selected' : '' }}>
                                                        {{ $field->label }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select name="filters[{{ $groupId }}][operator]" class="rounded-md border-gray-300">
                                                <option value="=" {{ ($filter['operator'] ?? '=') === '=' ? 'selected' : '' }}>=</option>
                                                <option value="!=" {{ ($filter['operator'] ?? '=') === '!=' ? 'selected' : '' }}>≠</option>
                                                <option value="contains" {{ ($filter['operator'] ?? '=') === 'contains' ? 'selected' : '' }}>Содержит</option>
                                            </select>

                                            <div class="value-container flex-grow">
                                                @php
                                                    $field = $fields->where('name', $filter['field'])->first();
                                                @endphp
                                                @if($field->type === 'date' || $field->type === 'time')
                                                    <div class="flex items-center gap-2">
                                                        <input type="{{ $field->type }}" 
                                                               name="filters[{{ $groupId }}][value][from]"
                                                               value="{{ $filter['value']['from'] ?? '' }}"
                                                               class="rounded-md border-gray-300">
                                                        <span>до</span>
                                                        <input type="{{ $field->type }}" 
                                                               name="filters[{{ $groupId }}][value][to]"
                                                               value="{{ $filter['value']['to'] ?? '' }}"
                                                               class="rounded-md border-gray-300">
                                                    </div>
                                                @elseif(in_array($field->type, ['select', 'radio', 'checkbox']))
                                                    <select name="filters[{{ $groupId }}][value][]" 
                                                            multiple
                                                            class="rounded-md border-gray-300">
                                                        @foreach($field->options as $option)
                                                            <option value="{{ $option }}"
                                                                    {{ in_array($option, (array)($filter['value'] ?? [])) ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input type="text" 
                                                           name="filters[{{ $groupId }}][value]"
                                                           value="{{ $filter['value'] ?? '' }}"
                                                           class="rounded-md border-gray-300">
                                                @endif
                                            </div>

                                            <button type="button" 
                                                    onclick="removeFilterGroup({{ $groupId }})" 
                                                    class="text-red-600 hover:text-red-900">
                                                Удалить
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-4 flex justify-between">
                            <button type="button" 
                                    onclick="addFilterGroup()" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Добавить фильтр
                            </button>
                            
                            <div class="space-x-4">
                                <a href="{{ route('forms.submissions', $form) }}" 
                                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                    Сбросить
                                </a>
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Применить фильтры
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Экспорт результатов</h3>
                    <div class="flex items-center gap-4">
                        <div class="inline-flex rounded-md shadow-sm">
                            <form action="{{ route('forms.download.csv', $form) }}" method="POST" class="inline">
                                @csrf
                                <!-- Передаем текущие фильтры -->
                                @if(request()->has('filters'))
                                    @foreach(request('filters') as $groupId => $filter)
                                        @foreach($filter as $key => $value)
                                            @if(is_array($value))
                                                @foreach($value as $k => $v)
                                                    <input type="hidden" name="filters[{{ $groupId }}][{{ $key }}][{{ $k }}]" value="{{ $v }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="filters[{{ $groupId }}][{{ $key }}]" value="{{ $value }}">
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-l-md hover:bg-blue-700">
                                    Скачать CSV
                                </button>
                            </form>
                            <form action="{{ route('forms.download.xlsx', $form) }}" method="POST" class="inline">
                                @csrf
                                <!-- Передаем текущие фильтры -->
                                @if(request()->has('filters'))
                                    @foreach(request('filters') as $groupId => $filter)
                                        @foreach($filter as $key => $value)
                                            @if(is_array($value))
                                                @foreach($value as $k => $v)
                                                    <input type="hidden" name="filters[{{ $groupId }}][{{ $key }}][{{ $k }}]" value="{{ $v }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="filters[{{ $groupId }}][{{ $key }}]" value="{{ $value }}">
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-r-md hover:bg-green-700 border-l border-white/20">
                                    Скачать Excel
                                </button>
                            </form>
                        </div>

                        <div class="border-l pl-4 ml-4">
                            <form action="{{ route('forms.download.template', $form) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Передаем текущие фильтры -->
                                @if(request()->has('filters'))
                                    @foreach(request('filters') as $groupId => $filter)
                                        @foreach($filter as $key => $value)
                                            @if(is_array($value))
                                                @foreach($value as $k => $v)
                                                    <input type="hidden" name="filters[{{ $groupId }}][{{ $key }}][{{ $k }}]" value="{{ $v }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="filters[{{ $groupId }}][{{ $key }}]" value="{{ $value }}">
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                                <div class="flex items-center gap-4">
                                    <input type="file" 
                                           name="template" 
                                           accept=".xlsx"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-blue-50 file:text-blue-700
                                                  hover:file:bg-blue-100">
                                    <button type="submit" 
                                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                        Заполнить шаблон
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Данные формы</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата отправки</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($submissions as $submission)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $submission->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            @foreach($fields as $field)
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-700">
                                                        {{ $field->label }}:
                                                    </span>
                                                    <span class="text-gray-900">
                                                        @php
                                                            $value = $submission->data[$field->name] ?? null;
                                                        @endphp
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @elseif($field->type === 'date')
                                                            {{ $value ? date('d.m.Y', strtotime($value)) : '' }}
                                                        @elseif($field->type === 'time')
                                                            {{ $value }}
                                                        @elseif($field->dictionary_id && $value)
                                                            {{ $field->dictionary->items->where('value', $value)->first()?->value ?? $value }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $submission->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleAll(source) {
            const checkboxes = document.getElementsByClassName('submission-checkbox');
            for (let checkbox of checkboxes) {
                checkbox.checked = source.checked;
            }
        }

        function deleteSelected() {
            const checkboxes = document.getElementsByClassName('submission-checkbox');
            const selected = Array.from(checkboxes).filter(cb => cb.checked).length;
            
            if (selected === 0) {
                alert('Выберите записи для удаления');
                return;
            }

            if (confirm(`Вы уверены, что хотите удалить ${selected} выбранных записей?`)) {
                document.getElementById('deleteForm').submit();
            }
        }

        function addFilterGroup() {
            const container = document.getElementById('filterContainer');
            const groupId = Date.now();
            
            const template = `
                <div class="filter-group bg-gray-50 p-4 rounded" data-group="${groupId}">
                    <div class="flex items-center gap-4">
                        ${container.children.length > 0 ? `
                            <select name="filters[${groupId}][logical_operator]" class="rounded-md border-gray-300">
                                <option value="and">И</option>
                                <option value="or">ИЛИ</option>
                            </select>
                        ` : ''}
                        
                        <select name="filters[${groupId}][field]" 
                                onchange="updateOperators(this)" 
                                class="rounded-md border-gray-300">
                            @foreach($fields as $field)
                                <option value="{{ $field->name }}" 
                                        data-type="{{ $field->type }}"
                                        data-options="{{ json_encode($field->options) }}">
                                    {{ $field->label }}
                                </option>
                            @endforeach
                        </select>

                        <select name="filters[${groupId}][operator]" class="rounded-md border-gray-300">
                            <option value="=">=</option>
                            <option value="!=">≠</option>
                            <option value="contains">Содержит</option>
                        </select>

                        <div class="value-container flex-grow">
                            <!-- Здесь будет динамически добавляться поле для значения -->
                        </div>

                        <button type="button" 
                                onclick="removeFilterGroup(${groupId})" 
                                class="text-red-600 hover:text-red-900">
                            Удалить
                        </button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', template);
            updateOperators(container.querySelector(`[data-group="${groupId}"] select[name*="[field]"]`));
        }

        function updateOperators(fieldSelect) {
            const group = fieldSelect.closest('.filter-group');
            const type = fieldSelect.selectedOptions[0].dataset.type;
            const options = JSON.parse(fieldSelect.selectedOptions[0].dataset.options || '[]');
            const valueContainer = group.querySelector('.value-container');
            
            // Очищаем контейнер значения
            valueContainer.innerHTML = '';
            
            switch (type) {
                case 'date':
                case 'time':
                    valueContainer.innerHTML = `
                        <div class="flex items-center gap-2">
                            <input type="${type}" 
                                   name="filters[${group.dataset.group}][value][from]"
                                   class="rounded-md border-gray-300">
                            <span>до</span>
                            <input type="${type}" 
                                   name="filters[${group.dataset.group}][value][to]"
                                   class="rounded-md border-gray-300">
                        </div>
                    `;
                    break;
                    
                case 'select':
                case 'radio':
                case 'checkbox':
                    valueContainer.innerHTML = `
                        <select name="filters[${group.dataset.group}][value][]" 
                                multiple
                                class="rounded-md border-gray-300">
                            ${options.map(opt => `
                                <option value="${opt}">${opt}</option>
                            `).join('')}
                        </select>
                    `;
                    break;
                    
                default:
                    valueContainer.innerHTML = `
                        <input type="text" 
                               name="filters[${group.dataset.group}][value]"
                               class="rounded-md border-gray-300">
                    `;
            }
        }

        function removeFilterGroup(groupId) {
            document.querySelector(`[data-group="${groupId}"]`).remove();
        }
    </script>
</body>
</html> 