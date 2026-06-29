@extends('layouts.app')

@section('title', 'Daftar Dosen')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    {{-- Header Halaman --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Dosen</h1>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $dosen->total() }} dosen terdaftar</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dosen.export', request()->query()) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-lg transition shadow-sm text-sm">
                <span>📊</span> <span>Export CSV</span>
            </a>
            <a href="{{ route('dosen.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-md transition flex items-center gap-2">
                <span>➕</span> <span>Tambah Dosen</span>
            </a>
        </div>
    </div>

    {{-- Form Pencarian dan Filter Jabatan --}}
    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mb-6">
        <form action="{{ route('dosen.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            {{-- Input Search --}}
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIDN atau nama dosen..." class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:border-blue-500">
            </div>
            
            {{-- Dropdown Filter Jabatan Fungsional --}}
            <div class="w-full md:w-48">
                <select name="jabatan" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:border-blue-500">
                    <option value="">-- Semua Jabatan --</option>
                    <option value="Asisten Ahli" {{ request('jabatan') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                    <option value="Lektor" {{ request('jabatan') == 'Lektor' ? 'selected' : '' }}>Lektor</option>
                    <option value="Kepala Lektor" {{ request('jabatan') == 'Kepala Lektor' ? 'selected' : '' }}>Kepala Lektor</option>
                    <option value="Guru Besar" {{ request('jabatan') == 'Guru Besar' ? 'selected' : '' }}>Guru Besar</option>
                </select>
            </div>

            {{-- Tombol Aksi Form --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-md text-sm transition">
                    Cari & Filter
                </button>
                @if(request()->anyFilled(['search', 'jabatan']))
                    <a href="{{ route('dosen.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium px-4 py-2 rounded-md text-sm transition text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Data Dosen --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-4">Foto</th>
                    
                    <th class="px-6 py-4">
                        <a href="{{ route('dosen.index', array_merge(request()->query(), ['sort_by' => 'nidn', 'sort_order' => ($sortBy == 'nidn' && $sortOrder == 'asc') ? 'desc' : 'asc'])) }}" 
                        class="inline-flex items-center gap-1 hover:text-gray-900 group transition">
                            <span>NIDN</span>
                            <span class="text-gray-400 group-hover:text-gray-600 text-xs font-mono">
                                @if($sortBy == 'nidn')
                                    {{ $sortOrder == 'asc' ? '▲' : '▼' }}
                                @else
                                    ↕
                                @endif
                            </span>
                        </a>
                    </th>

                    <th class="px-6 py-4">
                        <a href="{{ route('dosen.index', array_merge(request()->query(), ['sort_by' => 'nama', 'sort_order' => ($sortBy == 'nama' && $sortOrder == 'asc') ? 'desc' : 'asc'])) }}" 
                        class="inline-flex items-center gap-1 hover:text-gray-900 group transition">
                            <span>Nama Lengkap</span>
                            <span class="text-gray-400 group-hover:text-gray-600 text-xs font-mono">
                                @if($sortBy == 'nama')
                                    {{ $sortOrder == 'asc' ? '▲' : '▼' }}
                                @else
                                    ↕
                                @endif
                            </span>
                        </a>
                    </th>

                    <th class="px-6 py-4">
                        <a href="{{ route('dosen.index', array_merge(request()->query(), ['sort_by' => 'jabatan_fungsional', 'sort_order' => ($sortBy == 'jabatan_fungsional' && $sortOrder == 'asc') ? 'desc' : 'asc'])) }}" 
                        class="inline-flex items-center gap-1 hover:text-gray-900 group transition">
                            <span>Jabatan Fungsional</span>
                            <span class="text-gray-400 group-hover:text-gray-600 text-xs font-mono">
                                @if($sortBy == 'jabatan_fungsional')
                                    {{ $sortOrder == 'asc' ? '▲' : '▼' }}
                                @else
                                    ↕
                                @endif
                            </span>
                        </a>
                    </th>

                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($dosen as $item)
                <tr class="hover:bg-gray-50/70 transition">
                    {{-- Kolom Foto Profil --}}
                    <td class="px-6 py-4">
                        <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                    </td>
                    {{-- Kolom NIDN --}}
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nidn }}</td>
                    {{-- Kolom Nama --}}
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item->nama }}</td>
                    {{-- Kolom Jabatan --}}
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $item->jabatan_fungsional }}
                        </span>
                    </td>
                    {{-- Kolom Tombol Tindakan (CRUD) --}}
                    <td class="px-6 py-4 text-sm text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('dosen.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">Detail</a>
                            
                            <a href="{{ route('dosen.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium hover:underline">Edit</a>
                            
                            <form action="{{ route('dosen.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium hover:underline bg-transparent border-none p-0 cursor-pointer entry-btn">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- Menggunakan colspan="5" karena total ada 5 pasang tag <td> di atas --}}
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic bg-gray-50/50">
                        Tidak ada data dosen yang tersedia.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Pagination --}}
    <div class="mt-6">
        {{ $dosen->links() }}
    </div>
</div>
@endsection