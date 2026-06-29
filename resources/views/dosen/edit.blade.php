@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-6">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Data Dosen</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi dosen dengan data yang valid.</p>
        </div>
        <a href="{{ route('dosen.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
            Kembali
        </a>
    </div>

    {{-- Error Global --}}
    @if ($errors->any())
        <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded text-sm text-red-700">
            <p class="font-semibold mb-1">Periksa kembali inputan Anda:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Perbaruan --}}
    <form action="{{ route('dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Wajib untuk Method Spoofing Update di Laravel --}}

        {{-- Preview Foto Saat Ini (Jika ada) Sebelum Partial Form --}}
        @if($dosen->foto_profil)
            <div class="mb-5 bg-gray-50 p-3 rounded-lg border border-gray-200 flex items-center gap-3">
                <img src="{{ asset('storage/' . $dosen->foto_profil) }}"
                     class="w-14 h-14 object-cover rounded-full border border-gray-300 shadow-sm" alt="Foto Profil">
                <div>
                    <span class="block text-xs font-semibold text-gray-700">Foto Profil Aktif</span>
                    <span class="block text-2xs text-gray-400">Tersimpan di server</span>
                </div>
            </div>
        @endif

        {{-- Memanggil Partial Form milik Dosen  --}}
        @include('dosen._form')

        {{-- Button Aksi --}}
        <div class="flex justify-end gap-3 border-t border-gray-100 pt-4 mt-6">
            <button type="reset" class="bg-gray-50 hover:bg-gray-100 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                Reset
            </button>
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                Update Data
            </button>
        </div>

    </form>
</div>
@endsection