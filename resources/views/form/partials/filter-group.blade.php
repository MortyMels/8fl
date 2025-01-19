<div class="flex items-center gap-4">
    @if(!$isFirst)
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