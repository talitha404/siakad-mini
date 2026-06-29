@extends('layouts.app')

@section('title', 'Daftar Mata Kuliah - SIAKAD Mini')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Data Mata Kuliah</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data mata kuliah, filter program studi, dan pantau beban SKS.</p>
        </div>
        <div>
            <a href="{{ route('matakuliah.create') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2.5 rounded-lg transition shadow-sm">
                <span>➕</span> Tambah Mata Kuliah
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('matakuliah.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="prodi" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Program Studi</label>
                    <select name="prodi" id="prodi" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Semua Prodi --</option>
                        @foreach($daftarProdi as $p)
                            <option value="{{ $p }}" {{ request('prodi') == $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semester" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Semester</label>
                    <select name="semester" id="semester" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">-- Semua Semester --</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm text-center">
                        Filter
                    </button>
                    @if(request('prodi') || request('semester'))
                        <a href="{{ route('matakuliah.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition text-center flex items-center justify-center" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-linear-to-br from-blue-600 to-blue-700 text-white p-5 rounded-xl shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-100 uppercase tracking-wider">Beban Akumulasi SKS</p>
                <h3 class="text-sm text-blue-200 mt-1">
                    @if(request('prodi'))
                        Prodi: <span class="font-bold text-white">{{ request('prodi') }}</span>
                    @else
                        Semua Program Studi
                    @endif
                </h3>
            </div>
            <div class="flex items-baseline gap-2 mt-4 lg:mt-0">
                <span class="text-4xl font-extrabold tracking-tight">{{ $totalSks }}</span>
                <span class="text-sm text-blue-200 font-medium">Total SKS Diampu</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Kode MK</th>
                        <th class="px-6 py-4">Nama Mata Kuliah</th>
                        <th class="px-6 py-4 text-center">SKS</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4 text-center">Semester</th>
                        <th class="px-6 py-4">Dosen Pengampu</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($matakuliah as $mk)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4 font-mono font-semibold text-blue-600">{{ $mk->kode_mk }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $mk->nama_mk }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full border border-gray-200">
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $mk->prodi }}</td>
                            <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $mk->semester }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shadow-inner">
                                        {{ strtoupper(substr($mk->dosen->nama ?? 'D', 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $mk->dosen->nama ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('matakuliah.edit', $mk->id) }}" class="inline-flex items-center justify-center bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-2xs">
                                        ✏️ Edit
                                    </a>

                                    <form action="{{ route('matakuliah.destroy', $mk->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah {{ $mk->nama_mk }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-2xs cursor-pointer">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 text-3xl mb-2">📁</div>
                                <p class="text-gray-500 font-medium">Tidak ada data mata kuliah yang ditemukan.</p>
                                <p class="text-xs text-gray-400 mt-1">Coba ubah opsi pencarian atau tambah data baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($matakuliah->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $matakuliah->links() }}
            </div>
        @endif
    </div>
</div>
@endsection