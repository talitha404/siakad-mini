@extends('layouts.app')

@section('title', 'Detail Mata Kuliah - ' . $matakuliah->nama_mk)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="mb-2">
        <a href="{{ route('matakuliah.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            <span>←</span> Kembali ke Daftar Mata Kuliah
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-linear-to-r from-blue-600 to-blue-700 p-6 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="bg-blue-500/30 text-blue-100 text-xs font-mono font-bold px-3 py-1 rounded-md border border-white/10 uppercase tracking-wider">
                    {{ $matakuliah->kode_mk }}
                </span>
                <h1 class="text-2xl font-bold mt-2 tracking-tight">{{ $matakuliah->nama_mk }}</h1>
                <p class="text-sm text-blue-100 mt-1">{{ $matakuliah->prodi }} · Semester {{ $matakuliah->semester }}</p>
            </div>
            <div class="bg-white/10 px-4 py-3 rounded-lg border border-white/10 text-center sm:text-right self-start sm:self-center">
                <span class="block text-2xl font-extrabold tracking-tight">{{ $matakuliah->sks }}</span>
                <span class="text-xs font-medium text-blue-200 uppercase tracking-wider">Bobot SKS</span>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Informasi Akademik</h2>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div>
                    <dt class="text-sm text-gray-400">Kode Mata Kuliah</dt>
                    <dd class="font-mono font-semibold text-gray-900 mt-0.5 text-base">{{ $matakuliah->kode_mk }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Nama Mata Kuliah</dt>
                    <dd class="font-medium text-gray-900 mt-0.5 text-base">{{ $matakuliah->nama_mk }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Program Studi</dt>
                    <dd class="font-medium text-gray-900 mt-0.5">{{ $matakuliah->prodi }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Semester Batasan</dt>
                    <dd class="font-medium text-gray-900 mt-0.5">Semester {{ $matakuliah->semester }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Dibuat Pada</dt>
                    <dd class="font-medium text-gray-900 mt-0.5">{{ $matakuliah->created_at->format('d F Y (H:i)') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-400">Terakhir Diperbarui</dt>
                    <dd class="font-medium text-gray-900 mt-0.5">{{ $matakuliah->updated_at->format('d F Y (H:i)') }}</dd>
                </div>
            </dl>

            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Dosen Pengampu</h2>
            
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg shadow-sm shrink-0">
                    {{ strtoupper(substr($matakuliah->dosen->nama ?? 'D', 0, 1)) }}
                </div>
                
                <div class="flex-1 min-w-0">
                    @if($matakuliah->dosen)
                        <h3 class="text-base font-bold text-gray-900 truncate">{{ $matakuliah->dosen->nama }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">NIDN: <span class="font-mono font-medium text-gray-700">{{ $matakuliah->dosen->nidn }}</span></p>
                        <p class="text-xs text-gray-400 mt-0.5">Jabatan: {{ $matakuliah->dosen->jabatan_fungsional ?? 'Tidak Ada' }}</p>
                    @else
                        <h3 class="text-base font-bold text-red-600">Belum Ditentukan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Mata kuliah ini belum memiliki dosen pengampu resmi.</p>
                    @endif
                </div>
                
                @if($matakuliah->dosen)
                    <div>
                        <a href="{{ route('dosen.show', $matakuliah->dosen_id) }}" class="inline-flex items-center justify-center bg-white border border-gray-300 hover:border-blue-500 text-gray-700 hover:text-blue-600 text-xs font-medium px-3 py-2 rounded-lg transition shadow-sm" title="Lihat Profil Dosen">
                            Lihat Profil Dosen ↗
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection