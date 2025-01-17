<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class FormManagerController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Form::query();

        if ($request->user()) {
            // Для авторизованных пользователей показываем их формы, формы с доступом и публичные
            $query->where(function ($q) use ($request) {
                $q->where('user_id', $request->user()->id)
                  ->orWhereHas('sharedUsers', function ($q) use ($request) {
                      $q->where('users.id', $request->user()->id);
                  })
                  ->orWhere('is_public', true);
            });
        } else {
            // Для гостей показываем только публичные формы
            $query->where('is_public', true);
        }

        $forms = $query->latest()->get();
        return view('forms.index', compact('forms'));
    }

    public function create()
    {
        return view('forms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $form = $request->user()->forms()->create($validated);

        return redirect()->route('forms.index')
            ->with('success', 'Форма успешно создана');
    }

    public function edit(Form $form)
    {
        $this->authorize('update', $form);
        $users = User::where('id', '!=', auth()->id())->get();
        $sharedUsers = $form->sharedUsers()->pluck('id')->toArray();
        
        return view('forms.edit', compact('form', 'users', 'sharedUsers'));
    }

    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'shared_users' => 'array'
        ]);

        $form->update($validated);

        // Обновляем список пользователей с доступом
        if (isset($validated['shared_users'])) {
            $form->sharedUsers()->sync($validated['shared_users']);
        }

        return redirect()->route('forms.index')
            ->with('success', 'Форма успешно обновлена');
    }

    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);
        
        $form->delete();
        return redirect()->route('forms.index')
            ->with('success', 'Форма успешно удалена');
    }
} 