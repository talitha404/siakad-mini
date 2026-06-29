<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'prodi',
        'semester',
        'dosen_id', // Penting untuk menyimpan ID dosen pengampu
    ];

    /**
     * Casting tipe data kolom.
     */
    protected $casts = [
        'sks' => 'integer',
        'semester' => 'integer',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'id');
    }
}