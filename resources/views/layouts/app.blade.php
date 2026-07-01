<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD Mini')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 transition-colors duration-300 dark:bg-gray-900 dark:text-gray-100">
    {{-- Navbar --}}
    <nav class="bg-blue-700 text-white shadow-md transition-colors duration-300 dark:bg-slate-800">
        <div class="max-w-6xl mx-auto flex items-center justify-between px-4 py-4">
            <a href="/" class="flex items-center gap-2 text-xl font-bold">
                <span>🎓</span> <span>SIAKAD Mini</span>
            </a>
            <div class="flex items-center gap-4 sm:gap-6">
                <a href="{{ route('mahasiswa.index') }}" class="transition hover:text-blue-200 {{ request()->routeIs('mahasiswa.*') ? 'font-semibold underline' : '' }}">
                    Mahasiswa
                </a>
                <a href="{{ route('dosen.index') }}" class="transition hover:text-blue-200 {{ request()->routeIs('dosen.*') ? 'font-semibold underline' : '' }}">
                    Dosen
                </a>
                <a href="{{ route('matakuliah.index') }}" class="transition hover:text-blue-200 {{ request()->routeIs('matakuliah.*') ? 'font-semibold underline' : '' }}">
                    Mata Kuliah
                </a>
                <button id="theme-toggle" type="button" class="relative inline-flex h-8 w-16 items-center rounded-full border border-white/20 bg-white/20 p-1 shadow-inner transition duration-300 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Toggle dark mode" aria-pressed="false">
                    <span class="sr-only">Toggle dark mode</span>
                    <span id="theme-toggle-knob" class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-blue-700 shadow transition duration-300 translate-x-0">
                        <span id="theme-icon-sun" class="block transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="4.5"></circle>
                                <path d="M12 2.5v2.2M12 19.3v2.2M4.7 4.7l1.6 1.6M17.7 17.7l1.6 1.6M2.5 12h2.2M19.3 12h2.2M4.7 19.3l1.6-1.6M17.7 6.3l1.6-1.6"></path>
                            </svg>
                        </span>
                        <span id="theme-icon-moon" class="hidden transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M20 15.5A8.5 8.5 0 0 1 8.5 4a8.5 8.5 0 1 0 11.5 11.5Z"></path>
                            </svg>
                        </span>
                    </span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="mx-auto max-w-6xl px-4 py-8 transition-colors duration-300">
        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-12 border-t bg-white py-4 transition-colors duration-300 dark:bg-gray-800 dark:text-gray-200">
        <div class="mx-auto max-w-6xl px-4 text-center text-sm text-gray-600 dark:text-gray-300">
            &copy; {{ date('Y') }} SIAKAD Mini · Built with Laravel
        </div>
    </footer>

    <script>
        (function () {
            const storageKey = 'siakad-theme';
            const root = document.documentElement;
            const button = document.getElementById('theme-toggle');
            const knob = document.getElementById('theme-toggle-knob');
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');

            const applyTheme = (theme) => {
                const isDark = theme === 'dark';
                root.classList.toggle('dark', isDark);
                document.body.classList.toggle('dark', isDark);

                if (button) {
                    button.setAttribute('aria-pressed', String(isDark));
                }

                if (knob) {
                    knob.classList.toggle('translate-x-7', isDark);
                }

                if (sunIcon && moonIcon) {
                    sunIcon.classList.toggle('hidden', isDark);
                    moonIcon.classList.toggle('hidden', !isDark);
                }
            };

            const getStoredTheme = () => {
                const storedTheme = localStorage.getItem(storageKey);
                if (storedTheme === 'dark' || storedTheme === 'light') {
                    return storedTheme;
                }

                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };

            const toggleTheme = () => {
                const nextTheme = root.classList.contains('dark') ? 'light' : 'dark';
                localStorage.setItem(storageKey, nextTheme);
                applyTheme(nextTheme);
            };

            button?.addEventListener('click', toggleTheme);
            applyTheme(getStoredTheme());
        })();
    </script>
</body>
</html>