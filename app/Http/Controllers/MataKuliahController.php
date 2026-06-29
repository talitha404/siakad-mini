<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * READ — Tampilkan daftar mata kuliah dengan fitur filter dan total SKS.
     * Route: GET /matakuliah
     */
    public function index(Request $request)
    {
        $query = MataKuliah::with('dosen');

        // Filter berdasarkan Program Studi (Prodi)
        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        // Filter berdasarkan Semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Mengambil data hasil filter (tanpa paginasi terlebih dahulu untuk menghitung total SKS)
        $allFilteredData = $query->get();

        // Hitung total SKS dari data yang sudah terfilter (Tugas 2: Hitung total SKS per prodi/filter)
        $totalSks = $allFilteredData->sum('sks');

        // Jalankan paginasi untuk tampilan tabel (10 data per halaman)
        $matakuliah = $query->latest()->paginate(10)->withQueryString();

        // Mengambil daftar prodi unik untuk opsi di komponen filter view
        $daftarProdi = MataKuliah::pluck('prodi')->unique();

        return view('matakuliah.index', compact('matakuliah', 'totalSks', 'daftarProdi'));
    }

    /**
     * CREATE — Tampilkan form tambah mata kuliah.
     * Route: GET /matakuliah/create
     */
    public function create()
    {
        // Mengambil semua data dosen untuk dropdown relasi di form input
        $daftarDosen = Dosen::orderBy('nama', 'asc')->get();

        return view('matakuliah.create', compact('daftarDosen'));
    }

    /**
     * CREATE — Simpan data mata kuliah baru ke database.
     * Route: POST /matakuliah
     */
    public function store(Request $request)
    {
        // Validasi data input sesuai ketentuan format khusus di modul
        $request->validate([
            // Contoh validasi format khusus: TIF101 (3 huruf kapital diikuti 3 angka)
            'kode_mk' => 'required|string|regex:/^[A-Z]{3}[0-9]{3}$/|unique:matakuliah,kode_mk',
            'nama_mk' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'prodi' => 'required|string|max:50',
            'semester' => 'required|integer|min:1|max:8',
            'dosen_id' => 'required|exists:dosen,id', // Memastikan dosen_id valid ada di tabel dosen
        ], [
            'kode_mk.regex' => 'Format Kode MK harus berupa 3 huruf kapital diikuti 3 angka. Contoh: TIF101',
            'dosen_id.exists' => 'Dosen pengampu yang dipilih tidak valid.'
        ]);

        // Simpan data ke database jika validasi lolos
        MataKuliah::create([
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
            'dosen_id' => $request->dosen_id,
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('matakuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    }

    /**
     * SHOW — Tampilkan detail sebuah mata kuliah (Opsional pelengkap resource)
     * Route: GET /matakuliah/{id}
     */
    public function show(string $id)
    {
        $matakuliah = MataKuliah::with('dosen')->findOrFail($id);
        return view('matakuliah.show', compact('matakuliah'));
    }

    /**
     * UPDATE — Tampilkan form edit mata kuliah beserta data lamanya.
     * Route: GET /matakuliah/{id}/edit
     */
    public function edit(string $id)
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $daftarDosen = Dosen::orderBy('nama', 'asc')->get();

        return view('matakuliah.edit', compact('matakuliah', 'daftarDosen'));
    }

    /**
     * UPDATE — Simpan perubahan data mata kuliah ke database.
     * Route: PUT/PATCH /matakuliah/{id}
     */
    public function update(Request $request, string $id)
    {
        $matakuliah = MataKuliah::findOrFail($id);

        $request->validate([
            // Rule unique diabaikan untuk ID mata kuliah yang sedang di-edit ini sendiri
            'kode_mk' => 'required|string|regex:/^[A-Z]{3}[0-9]{3}$/|unique:matakuliah,kode_mk,' . $matakuliah->id,
            'nama_mk' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'prodi' => 'required|string|max:50',
            'semester' => 'required|integer|min:1|max:8',
            'dosen_id' => 'required|exists:dosen,id',
        ], [
            'kode_mk.regex' => 'Format Kode MK harus berupa 3 huruf kapital diikuti 3 angka. Contoh: TIF101',
            'dosen_id.exists' => 'Dosen pengampu tidak valid.'
        ]);

        $matakuliah->update([
            'kode_mk' => $request->kode_mk,
            'nama_mk' => $request->nama_mk,
            'sks' => $request->sks,
            'prodi' => $request->prodi,
            'semester' => $request->semester,
            'dosen_id' => $request->dosen_id,
        ]);

        return redirect()->route('matakuliah.index')->with('success', 'Mata Kuliah berhasil diperbarui!');
    }

    /**
     * DELETE — Hapus data mata kuliah dari database.
     * Route: DELETE /matakuliah/{id}
     */
    public function destroy(string $id)
    {
        $matakuliah = MataKuliah::findOrFail($id);
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')->with('success', 'Mata Kuliah berhasil dihapus!');
    }
}