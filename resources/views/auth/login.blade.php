@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold text-gray-900">Вход в систему</h2>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="form-label">Имя пользователя</label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               class="form-input"
                               required 
                               autofocus>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="form-input"
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="w-full btn btn-primary">
                            Войти
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
