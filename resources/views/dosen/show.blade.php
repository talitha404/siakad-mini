@extends('layouts.app')

@section('title', 'Detail Dosen - ' . $dosen->nama)

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm overflow-hidden">
    {{-- Header Detail --}}
    <div class="bg-linear-to-r bg-blue-700 p-6 text-white flex items-center justify-between">
        <div>
            <a href="{{ route('dosen.index') }}" class="text-blue-200 hover:text-white text-sm font-medium transition">
                ← Kembali ke Daftar
            </a>
            <h1 class="text-2xl font-bold mt-2">Profil Detail Dosen</h1>
        </div>
        <a href="{{ route('dosen.edit', $dosen->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-4 py-2 rounded-md text-sm transition shadow-sm">
            Edit Profil
        </a>
    </div>

    {{-- Konten Utama --}}
    <div class="p-6 flex flex-col md:flex-row gap-8 items-center md:items-start">
        {{-- Bagian Foto Profil --}}
        <div class="w-40 h-40 shrink-0">
            <img src="{{ $dosen->foto_url }}" alt="{{ $dosen->nama }}" class="w-full h-full rounded-full object-cover border-4 border-gray-100 shadow-md">
        </div>

            {{-- Bagian Tabel Informasi --}}
                <div class="flex-1 w-full">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">{{ $dosen->nama }}</h2>
                    
                    <div class="space-y-3">
                        <div class="flex border-b border-gray-100 pb-2">
                            <span class="w-40 font-medium text-gray-500 text-sm">NIDN</span>
                            <span class="text-gray-800 text-sm font-semibold">{{ $dosen->nidn }}</span>
                        </div>
                        
                        <div class="flex border-b border-gray-100 pb-2">
                            <span class="w-40 font-medium text-gray-500 text-sm">Nama Lengkap</span>
                            <span class="text-gray-800 text-sm">{{ $dosen->nama }}</span>
                        </div>
                        
                        <div class="flex border-b border-gray-100 pb-2">
                            <span class="w-40 font-medium text-gray-500 text-sm">Jabatan Fungsional</span>
                            <span class="text-sm">
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium">
                                    {{ $dosen->jabatan_fungsional }}
                                </span>
                            </span>
                        </div>

                        {{-- Tambahan Tugas 1: Daftar Mata Kuliah yang Diampu --}}
                        <div class="flex flex-col border-b border-gray-100 pb-3 pt-1">
                            <span class="font-medium text-gray-500 text-sm mb-2">Mata Kuliah Yang Diampu</span>
                            
                            @if($dosen->mataKuliah->isEmpty())
                                <span class="text-sm text-gray-400 italic bg-gray-50 px-3 py-2 rounded-lg border border-dashed border-gray-200">
                                    Dosen ini belum mengampu mata kuliah apa pun.
                                </span>
                            @else
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                                    <table class="w-full text-left border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-200 font-semibold text-gray-600 uppercase tracking-wider">
                                                <th class="px-4 py-2">Kode</th>
                                                <th class="px-4 py-2">Nama Mata Kuliah</th>
                                                <th class="px-4 py-2 text-center">SKS</th>
                                                <th class="px-4 py-2">Prodi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-gray-700">
                                            @foreach($dosen->mataKuliah as $mk)
                                                <tr class="hover:bg-gray-50/50 transition">
                                                    <td class="px-4 py-2 font-mono font-semibold text-blue-600">{{ $mk->kode_mk }}</td>
                                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $mk->nama_mk }}</td>
                                                    <td class="px-4 py-2 text-center font-bold text-gray-600">{{ $mk->sks }}</td>
                                                    <td class="px-4 py-2 text-gray-500">{{ $mk->prodi }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="flex border-b border-gray-100 pb-2">
                            <span class="w-40 font-medium text-gray-500 text-sm">Terdaftar Pada</span>
                            <span class="text-gray-600 text-sm">{{ $dosen->created_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>

                        <div class="flex pb-2">
                            <span class="w-40 font-medium text-gray-500 text-sm">Pembaruan Terakhir</span>
                            <span class="text-gray-600 text-sm">{{ $dosen->updated_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection