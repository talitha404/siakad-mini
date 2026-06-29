<?php

use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MataKuliahController;
use Illuminate\Support\Facades\Route;

// secara dasar akan menampilkan layout mahasiswa
Route::get('/', function () {
    return redirect()->route('mahasiswa.index');
});

// Route untuk import data mahasiswa dari CSV
Route::post('mahasiswa/import', [MahasiswaController::class, 'importCsv'])->name('mahasiswa.import');

// Route untuk export data mahasiswa ke CSV 
Route::get('mahasiswa/export', [MahasiswaController::class, 'exportCsv'])->name('mahasiswa.export');

// Resource route untuk Mahasiswa
Route::resource('mahasiswa', MahasiswaController::class);

// Route untuk import data dosen dari CSV
Route::post('dosen/import', [DosenController::class, 'importCsv'])->name('dosen.import');

// Route untuk export data dosen ke CSV
Route::get('dosen/export', [DosenController::class, 'exportCsv'])->name('dosen.export');

// Resource route untuk CRUD Dosen
Route::resource('dosen', DosenController::class);

// Resource route untuk CRUD Mata Kuliah
Route::resource('matakuliah', MataKuliahController::class);