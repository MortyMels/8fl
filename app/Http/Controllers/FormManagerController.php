<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Получаем только формы текущего пользователя
        $forms = Auth::user()->forms()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('form.index', compact('forms'));
    }

    // Добавляем метод create
    public function create()
    {
        return view('forms.create');
    }

    // Добавляем метод store для сохранения формы
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $form = Auth::user()->forms()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_public' => $validated['is_public'] ?? false
        ]);

        return redirect()
            ->route('forms.edit', $form)
            ->with('success', 'Форма успешно создана');
    }

    // Добавляем метод edit
    public function edit(Form $form)
    {
        $this->authorize('update', $form);
        
        // Получаем список пользователей, кроме текущего
        $users = User::where('id', '!=', auth()->id())->get();
        // Получаем ID пользователей, которым предоставлен доступ
        $sharedUsers = $form->sharedUsers()->pluck('users.id')->toArray();

        return view('forms.edit', compact('form', 'users', 'sharedUsers'));
    }

    // Добавляем метод update
    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'shared_users' => 'nullable|array',
            'shared_users.*' => 'exists:users,id'
        ]);

        $form->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_public' => $validated['is_public'] ?? false
        ]);

        // Обновляем список пользователей с доступом
        if (isset($validated['shared_users'])) {
            $form->sharedUsers()->sync($validated['shared_users']);
        } else {
            $form->sharedUsers()->detach();
        }

        return redirect()
            ->route('forms.index')
            ->with('success', 'Форма успешно обновлена');
    }

    // Добавляем метод destroy
    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);
        
        $form->delete();

        return redirect()
            ->route('forms.index')
            ->with('success', 'Форма успешно удалена');
    }

    // ... остальные методы ...
} 