<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        /* Базовые компоненты */
        .btn {
            @apply inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed;
        }
        .btn-primary {
            @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
        }
        .btn-secondary {
            @apply bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
        }
        .btn-danger {
            @apply bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2;
        }

        /* Формы */
        .form-input {
            @apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
        .form-select {
            @apply block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm;
        }

        /* Карточки */
        .card {
            @apply bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden;
        }
        .card-header {
            @apply px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50;
        }
        .card-body {
            @apply px-4 py-5 sm:p-6;
        }
        .card-footer {
            @apply px-4 py-4 sm:px-6 border-t border-gray-200 bg-gray-50;
        }

        /* Адаптивный контейнер */
        .container {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .container { max-width: 640px; }
        }
        @media (min-width: 768px) {
            .container { max-width: 768px; }
        }
        @media (min-width: 1024px) {
            .container { max-width: 1024px; }
        }
        @media (min-width: 1280px) {
            .container { max-width: 1280px; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Навигация -->
    <nav class="bg-white border-b border-gray-200">
        <div class="container">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Логотип -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('forms.index') }}" class="text-xl font-bold text-blue-600">
                            {{ config('app.name') }}
                        </a>
                    </div>

                    <!-- Основная навигация -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('forms.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('forms.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                            Формы
                        </a>
                        <a href="{{ route('dictionaries.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->routeIs('dictionaries.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">
                            Справочники
                        </a>
                    </div>
                </div>

                <!-- Правая часть навигации -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    Выход
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Мобильное меню -->
                <div class="flex items-center sm:hidden">
                    <button type="button" 
                            onclick="toggleMobileMenu()"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Мобильная навигация -->
            <div class="sm:hidden hidden" id="mobileMenu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('forms.index') }}" 
                       class="block pl-3 pr-4 py-2 text-base font-medium {{ request()->routeIs('forms.*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-700' : 'border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Формы
                    </a>
                    <a href="{{ route('dictionaries.index') }}" 
                       class="block pl-3 pr-4 py-2 text-base font-medium {{ request()->routeIs('dictionaries.*') ? 'bg-blue-50 border-l-4 border-blue-500 text-blue-700' : 'border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }}">
                        Справочники
                    </a>
                </div>
                @auth
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-600 hover:bg-gray-100">
                                    Выход
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Основной контент -->
    <main class="flex-1 container py-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Подвал -->
    <footer class="bg-white border-t border-gray-200">
        <div class="container py-4">
            <div class="text-center text-sm text-gray-500">
                © {{ date('Y') }} {{ config('app.name') }}. Все права защищены.
            </div>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 640) {
                const menu = document.getElementById('mobileMenu');
                menu.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
