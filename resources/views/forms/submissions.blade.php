@extends('layouts.app')

@section('title', 'Результаты - ' . $form->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Верхняя панель -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Результаты формы</h1>
                <p class="mt-1 text-gray-500">{{ $form->name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('forms.show', $form) }}" 
                   class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    К форме
                </a>
            </div>
        </div>
    </div>

    <!-- Таблица с результатами -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($submissions->isEmpty())
            <div class="p-6 text-center text-gray-500">
                <p class="text-lg">Пока нет ответов</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Дата
                            </th>
                            @foreach($form->fields()->orderBy('sort_order')->get() as $field)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $field->label }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $submission->created_at->format('d.m.Y H:i') }}
                                </td>
                                @foreach($form->fields()->orderBy('sort_order')->get() as $field)
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        @if(isset($submission->data[$field->id]))
                                            @if(is_array($submission->data[$field->id]))
                                                {{ implode(', ', $submission->data[$field->id]) }}
                                            @else
                                                {{ $submission->data[$field->id] }}
                                            @endif
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Пагинация -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 