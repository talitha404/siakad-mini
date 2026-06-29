<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 10)->unique(); 
            $table->string('nama_mk', 100);
            $table->integer('sks');
            $table->string('prodi', 50); // Untuk keperluan filter & perhitungan total SKS per prodi
            $table->integer('semester'); // Untuk keperluan filter berdasarkan semester
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
};