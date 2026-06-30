<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * Export data mata kuliah ke CSV.
     * Route: GET /matakuliah/export
     *
     * Meniru logika export mahasiswa dengan:
     * - filter prodi dan semester
     * - sorting aman berdasarkan kolom yang valid
     * - men-stream hasil sebagai file CSV
     */
    public function exportCsv(Request $request)
    {
        $query = MataKuliah::with('dosen');

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $sortColumn = $request->input('sort', 'kode_mk');
        $direction = $request->input('direction', 'asc');
        $validColumns = ['kode_mk', 'nama_mk', 'sks', 'prodi', 'semester'];

        if (in_array($sortColumn, $validColumns)) {
            $query->orderBy($sortColumn, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('kode_mk', 'asc');
        }

        $matakuliah = $query->get();
        $fileName = 'data-matakuliah-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function () use ($matakuliah) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Kode MK', 'Nama Mata Kuliah', 'SKS', 'Program Studi', 'Semester', 'Dosen ID']);

            foreach ($matakuliah as $index => $mk) {
                fputcsv($handle, [
                    $index + 1,
                    $mk->kode_mk,
                    $mk->nama_mk,
                    $mk->sks,
                    $mk->prodi,
                    $mk->semester,
                    $mk->dosen_id,
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);

        return $response;
    }

    /**
     * Import data mata kuliah dari file CSV.
     * Route: POST /matakuliah/import
     *
     * Mirip dengan import mahasiswa:
     * - validasi file CSV
     * - deteksi separator koma atau titik koma
     * - baca setiap baris CSV
     * - validasi setiap baris dan simpan data yang valid
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file_csv');
        $content = file_get_contents($file->getRealPath());
        $separator = (substr_count($content, ';') > substr_count($content, ',')) ? ';' : ',';

        $handle = fopen($file->getRealPath(), 'r');
        fgetcsv($handle, 1000, $separator);

        $successCount = 0;
        $failedRows = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle, 1000, $separator)) !== false) {
            $rowNum++;
            if (empty(array_filter($row))) {
                continue;
            }

            $data = [
                'kode_mk' => $row[1] ?? null,
                'nama_mk' => $row[2] ?? null,
                'sks' => $row[3] ?? null,
                'prodi' => $row[4] ?? null,
                'semester' => $row[5] ?? null,
                'dosen_id' => $row[6] ?? null,
            ];

            $validator = Validator::make($data, [
                'kode_mk' => 'required|string|regex:/^[A-Z]{3}[0-9]{3}$/|unique:matakuliah,kode_mk',
                'nama_mk' => 'required|string|max:100',
                'sks' => 'required|integer|min:1|max:6',
                'prodi' => 'required|string|max:50',
                'semester' => 'required|integer|min:1|max:8',
                'dosen_id' => 'required|exists:dosen,id',
            ], [
                'kode_mk.regex' => 'Format Kode MK harus berupa 3 huruf kapital diikuti 3 angka. Contoh: TIF101',
                'dosen_id.exists' => 'Dosen pengampu tidak valid.',
            ]);

            if ($validator->fails()) {
                $failedRows[] = [
                    'baris' => $rowNum,
                    'identitas' => $data['kode_mk'] ?: 'Tanpa Kode MK',
                    'pesan' => implode(', ', $validator->errors()->all()),
                ];
                continue;
            }

            MataKuliah::create($data);
            $successCount++;
        }

        fclose($handle);

        return redirect()->route('matakuliah.index')->with([
            'import_status' => true,
            'jumlah_berhasil' => $successCount,
            'daftar_gagal' => $failedRows,
        ]);
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