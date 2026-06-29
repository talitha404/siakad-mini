<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIAKAD Mini')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    {{-- Navbar --}}
    <nav class="bg-blue-700 text-white shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-xl font-bold">
                <span>🎓</span> <span>SIAKAD Mini</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('mahasiswa.index') }}" class="hover:text-blue-200 transition {{ request()->routeIs('mahasiswa.*') ? 'font-semibold underline' : '' }}">
                    Mahasiswa
                </a>
                <a href="{{ route('dosen.index') }}" class="hover:text-blue-200 transition {{ request()->routeIs('dosen.*') ? 'font-semibold underline' : '' }}">
                    Dosen
                </a>
                <a href="{{ route('matakuliah.index') }}" class="hover:text-blue-200 transition {{ request()->routeIs('matakuliah.*') ? 'font-semibold underline' : '' }}">
                    Mata Kuliah
                </a>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-6xl mx-auto px-4 py-8">
        {{-- Flash Message Success --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-12 py-4">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-600">
            &copy; {{ date('Y') }} SIAKAD Mini · Built with Laravel
        </div>
    </footer>
</body>
</html>