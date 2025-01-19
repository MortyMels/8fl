<?php

namespace App\Http\Controllers;

use App\Models\Dictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DictionaryController extends Controller
{
    public function index()
    {
        $dictionaries = Dictionary::where('user_id', auth()->id())
            ->orWhere('is_public', true)
            ->withCount('items')
            ->get();

        return view('dictionaries.index', compact('dictionaries'));
    }

    public function create()
    {
        return view('dictionaries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $dictionary = auth()->user()->dictionaries()->create($validated);

        return redirect()->route('dictionaries.edit', $dictionary)
            ->with('success', 'Справочник успешно создан');
    }

    public function edit(Dictionary $dictionary)
    {
        $this->authorize('update', $dictionary);
        
        $dictionary->load('items');
        
        return view('dictionaries.edit', compact('dictionary'));
    }

    public function update(Request $request, Dictionary $dictionary)
    {
        $this->authorize('update', $dictionary);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'values' => 'required|string'
        ]);

        $dictionary->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_public' => $validated['is_public'] ?? false
        ]);

        $values = array_filter(explode("\n", $validated['values']));
        
        $dictionary->items()->delete();
        
        foreach($values as $index => $value) {
            $dictionary->items()->create([
                'value' => trim($value),
                'sort_order' => $index + 1
            ]);
        }

        return redirect()->route('dictionaries.index')
            ->with('success', 'Справочник успешно обновлен');
    }

    public function destroy(Dictionary $dictionary)
    {
        $this->authorize('delete', $dictionary);
        
        $dictionary->delete();

        return redirect()->route('dictionaries.index')
            ->with('success', 'Справочник успешно удален');
    }

    public function export(Dictionary $dictionary)
    {
        $this->authorize('update', $dictionary);

        $data = [
            'dictionary' => [
                'name' => $dictionary->name,
                'description' => $dictionary->description,
                'is_public' => $dictionary->is_public,
            ],
            'items' => $dictionary->items()
                ->orderBy('sort_order')
                ->pluck('value')
                ->toArray()
        ];

        $filename = slug($dictionary->name) . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename=' . $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048'
        ]);

        try {
            $content = file_get_contents($request->file('file')->path());
            $data = json_decode($content, true);

            if (!isset($data['dictionary']) || !isset($data['items'])) {
                throw new \Exception('Неверный формат файла');
            }

            DB::beginTransaction();

            // Создаем справочник
            $dictionary = auth()->user()->dictionaries()->create([
                'name' => $data['dictionary']['name'] . ' (Импорт)',
                'description' => $data['dictionary']['description'],
                'is_public' => $data['dictionary']['is_public'],
            ]);

            // Создаем элементы
            foreach ($data['items'] as $index => $value) {
                $dictionary->items()->create([
                    'value' => $value,
                    'sort_order' => $index + 1
                ]);
            }

            DB::commit();

            return redirect()->route('dictionaries.index')
                ->with('success', 'Справочник успешно импортирован');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ошибка при импорте справочника: ' . $e->getMessage());
        }
    }
} 