<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua ID dosen yang ada di database
        $dosenIds = Dosen::pluck('id')->toArray();

        // Antisipasi jika kamu belum mengisi seeder Dosen
        if (empty($dosenIds)) {
            $this->command->warn('Seeder Mata Kuliah dilewati karena belum ada data di tabel dosen. Silakan jalankan DosenSeeder terlebih dahulu!');
            return;
        }

        // 2. Siapkan data sample Mata Kuliah (Gunakan array_random atau index untuk dosen_id)
        $data = [
            [
                'kode_mk' => 'TIF101',
                'nama_mk' => 'Pemrograman Web',
                'sks' => 3,
                'prodi' => 'Informatika',
                'semester' => 4,
                'dosen_id' => $dosenIds[0], // Diampu oleh dosen pertama
            ],
            [
                'kode_mk' => 'TIF102',
                'nama_mk' => 'Basis Data',
                'sks' => 4,
                'prodi' => 'Informatika',
                'semester' => 2,
                'dosen_id' => $dosenIds[0], 
            ],
            [
                'kode_mk' => 'TIF201',
                'nama_mk' => 'Struktur Data',
                'sks' => 3,
                'prodi' => 'Informatika',
                'semester' => 3,
                'dosen_id' => $dosenIds[1] ?? $dosenIds[0], // Dosen kedua (jika ada)
            ],
            [
                'kode_mk' => 'TIF202',
                'nama_mk' => 'Kecerdasan Buatan',
                'sks' => 3,
                'prodi' => 'Informatika',
                'semester' => 5,
                'dosen_id' => $dosenIds[1] ?? $dosenIds[0],
            ],
            [
                'kode_mk' => 'SID101',
                'nama_mk' => 'Analisis Sistem Informasi',
                'sks' => 3,
                'prodi' => 'Sistem Informasi',
                'semester' => 4,
                'dosen_id' => $dosenIds[2] ?? $dosenIds[0], // Dosen ketiga (jika ada)
            ],
            [
                'kode_mk' => 'SID102',
                'nama_mk' => 'Manajemen Proyek TI',
                'sks' => 2,
                'prodi' => 'Sistem Informasi',
                'semester' => 6,
                'dosen_id' => $dosenIds[2] ?? $dosenIds[0],
            ],
        ];

        // 3. Looping dan masukkan data ke database
        foreach ($data as $row) {
            MataKuliah::create($row);
        }

        $this->command->info('MataKuliahSeeder berhasil dijalankan!');
    }
}