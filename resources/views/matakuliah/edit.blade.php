@extends('layouts.app')

@section('title', 'Edit Mata Kuliah - SIAKAD Mini')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('matakuliah.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition mb-1">
                <span>←</span> Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Data Mata Kuliah</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi mata kuliah secara akurat di bawah ini.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
       
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                <div class="flex items-center gap-2 mb-2 text-red-800 font-semibold text-sm">
                    <span>⚠️</span> Terdapat kesalahan pengisian data:
                </div>
                <ul class="list-disc list-inside text-xs text-red-700 space-y-1 pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('matakuliah.update', $matakuliah->id) }}" method="POST" class="space-y-6" novalidate>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="kode_mk" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Kode Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_mk" id="kode_mk" 
                        value="{{ old('kode_mk', $matakuliah->kode_mk) }}" 
                        placeholder="Contoh: TIF101" 
                        required 
                        class="w-full bg-gray-50 border rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:outline-none transition uppercase 
                        {{ $errors->has('kode_mk') 
                            ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                            : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                    <p class="text-xs text-gray-400 mt-1">Gunakan 3 huruf kapital diikuti 3 angka.</p>
                </div>

                <div>
                    <label for="nama_mk" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_mk" id="nama_mk" 
                        value="{{ old('nama_mk', $matakuliah->nama_mk) }}" 
                        placeholder="Contoh: Pemrograman Web" 
                        required 
                        class="w-full bg-gray-50 border rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:outline-none transition
                        {{ $errors->has('nama_mk') 
                            ? 'border-red-300 focus:ring-red-500 focus:border-red-500' 
                            : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500' }}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="sks" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Bobot SKS <span class="text-red-500">*</span>
                    </label>
                    <select name="sks" id="sks" required 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                        <option value="">-- Pilih Jumlah SKS --</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('sks', $matakuliah->sks) == $i ? 'selected' : '' }}>{{ $i }} SKS</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester" id="semester" required 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                        <option value="">-- Pilih Semester --</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester', $matakuliah->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="prodi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <select name="prodi" id="prodi" required 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                        <option value="">-- Pilih Program Studi --</option>
                        <option value="Informatika" {{ old('prodi', $matakuliah->prodi) == 'Informatika' ? 'selected' : '' }}>Informatika</option>
                        <option value="Sistem Informasi" {{ old('prodi', $matakuliah->prodi) == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="Sains Data" {{ old('prodi', $matakuliah->prodi) == 'Sains Data' ? 'selected' : '' }}>Sains Data</option>
                    </select>
                </div>

                <div>
                    <label for="dosen_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Dosen Pengampu <span class="text-red-500">*</span>
                    </label>
                    <select name="dosen_id" id="dosen_id" required 
                        class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3.5 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition">
                        <option value="">-- Pilih Dosen Pengampu --</option>
                        @forelse($daftarDosen as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id', $matakuliah->dosen_id) == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @empty
                            <option value="" disabled class="text-red-500">⚠️ Belum ada data dosen di database!</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="reset" class="bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium px-5 py-2.5 rounded-lg transition">
                    Reset Form
                </button>
                <a href="{{ route('matakuliah.index') }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2.5 rounded-lg transition">
                    Batal
                </a>
                <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition shadow-sm">
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection