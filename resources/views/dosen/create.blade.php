@extends('layouts.app')

@section('title', 'Tambah Dosen Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6 mt-6">

    {{-- Header --}}
    <div class="mb-6 border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Dosen Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini dengan data yang valid.</p>
    </div>

    {{-- Error Global --}}
    @if ($errors->any())
        <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded text-sm text-red-700">
            <p class="font-semibold mb-1">Periksa kembali isian Anda:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('dosen.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Memanggil Partial Form milik Dosen (Tugas 1) --}}
        @include('dosen._form')

        {{-- Button Aksi --}}
        <div class="flex justify-end gap-3 border-t border-gray-100 pt-4 mt-6">
            <a href="{{ route('dosen.index') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                Batal
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection