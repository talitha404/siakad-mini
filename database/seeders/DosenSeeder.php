<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nidn' => '071203850100001',
                'nama' => 'Dr. Budi Santoso, M.Kom',
                'jabatan_fungsional' => 'Kepala Lektor',
                'foto_profil' => null,
            ],
            [
                'nidn' => '071505900200002',
                'nama' => 'Siti Aminah, S.T., M.T.',
                'jabatan_fungsional' => 'Lektor',
                'foto_profil' => null,
            ],
            [
                'nidn' => '072010920300003',
                'nama' => 'Andi Wijaya, M.Sc',
                'jabatan_fungsional' => 'Asisten Ahli',
                'foto_profil' => null,
            ],
            [
                'nidn' => '070101800400004',
                'nama' => 'Prof. Dr. Ir. Retno Wahyuni',
                'jabatan_fungsional' => 'Guru Besar',
                'foto_profil' => null,
            ],
        ];

        foreach ($data as $row) {
            Dosen::create($row);
        }
    }
}