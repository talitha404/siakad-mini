<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * READ — Tampilkan daftar mahasiswa
     * Route: GET /mahasiswa
     */
public function index(Request $request)
    {
        $query = Mahasiswa::query();

        // Pencarian berdasarkan NIM, nama, atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan prodi
        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sortColumn = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');

        // Daftar kolom yang diizinkan untuk di-sort (mencegah SQL Injection)
        $kolomValid = ['nim', 'nama', 'prodi', 'angkatan', 'ipk', 'id'];

        if (in_array($sortColumn, $kolomValid)) {
            // Urutkan data berdasarkan parameter yang aktif
            $query->orderBy($sortColumn, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            // Fallback jika kolom tidak valid, urutkan berdasarkan data terbaru
            $query->latest();
        }

        // Jalankan paginasi (withQueryString memastikan parameter sort & filter tidak hilang saat pindah halaman)
        $mahasiswa = $query->paginate(10)->withQueryString();

        return view('mahasiswa.index', compact('mahasiswa'));
    }

    /**
     * CREATE — Tampilkan form tambah mahasiswa
     * Route: GET /mahasiswa/create
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * CREATE — Simpan data mahasiswa baru
     * Route: POST /mahasiswa
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nim' => 'required|string|max:10|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:mahasiswa,email',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'prodi' => 'required|string|max:50',
            'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
            'ipk' => 'nullable|numeric|min:0|max:4',
            'status' => 'required|in:aktif,cuti,lulus,do',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Upload foto (jika ada)
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('mahasiswa', 'public');
            $validated['foto'] = $path;
        }

        // Simpan ke database
        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    /**
     * READ — Tampilkan detail mahasiswa
     * Route: GET /mahasiswa/{id}
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * UPDATE — Tampilkan form edit
     * Route: GET /mahasiswa/{id}/edit
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * UPDATE — Update data mahasiswa
     * Route: PUT/PATCH /mahasiswa/{id}
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:10|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:mahasiswa,email,' . $mahasiswa->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'prodi' => 'required|string|max:50',
            'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
            'ipk' => 'nullable|numeric|min:0|max:4',
            'status' => 'required|in:aktif,cuti,lulus,do',
        ]);

        // Upload foto baru (jika ada)
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($mahasiswa->foto) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }

            $path = $request->file('foto')->store('mahasiswa', 'public');
            $validated['foto'] = $path;
        }

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * DELETE — Hapus mahasiswa
     * Route: DELETE /mahasiswa/{id}
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        // Hapus foto dari storage (jika ada)
        if ($mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }

        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * Export data mahasiswa ke CSV
     * Route: GET /mahasiswa/export
     */
    public function exportCsv(Request $request)
    {
        $query = Mahasiswa::query();

        // 1. Terapkan Filter yang sama persis seperti di halaman index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                ->orWhere('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Terapkan juga sorting jika sedang aktif
        $sortColumn = $request->input('sort', 'id');
        $direction = $request->input('direction', 'desc');
        $kolomValid = ['nim', 'nama', 'prodi', 'angkatan', 'ipk', 'id'];
        
        if (in_array($sortColumn, $kolomValid)) {
            $query->orderBy($sortColumn, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        // 2. Ambil semua data hasil filter (tanpa paginasi)
        $mahasiswa = $query->get();

        // 3. Proses pembuatan stream file CSV
        $fileName = 'data-mahasiswa-' . now()->format('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function () use ($mahasiswa) {
            $handle = fopen('php://output', 'w');
            
            // Tulis header kolom di file Excel/CSV
            fputcsv($handle, ['No', 'NIM', 'Nama', 'Email', 'Program Studi', 'Angkatan', 'IPK', 'Status']);

            // Tulis data baris demi baris
            foreach ($mahasiswa as $index => $mhs) {
                fputcsv($handle, [
                    $index + 1,
                    $mhs->nim,
                    $mhs->nama,
                    $mhs->email,
                    $mhs->prodi,
                    $mhs->angkatan,
                    $mhs->ipk,
                    $mhs->status,
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
     * Import data mahasiswa dari file CSV
     * Route: POST /mahasiswa/import
     */
    public function importCsv(Request $request)
    {
        // 1. Validasi awal: Pastikan file diunggah dan berformat CSV/txt
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048'
        ]);
        $file = $request->file('file_csv');
        
        // Trik Otomatis: Deteksi apakah CSV menggunakan koma (,) atau titik koma (;)
        $fileContent = file_get_contents($file->getRealPath());
        $separator = (substr_count($fileContent, ';') > substr_count($fileContent, ',')) ? ';' : ',';

        $handle = fopen($file->getRealPath(), 'r');
        // Ambil baris pertama sebagai header kolom
        $header = fgetcsv($handle, 1000, $separator);
        
        $berhasil = 0;
        $gagalRows = []; 
        $rowNum = 1;

        // 2. Looping membaca isi baris file CSV
        while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
            $rowNum++;

            // Lewati jika baris kosong
            if (empty(array_filter($row))) continue;

            // PERBAIKAN: Gunakan strtolower untuk status agar sinkron dengan view/database
            $statusRaw = isset($row[7]) ? strtolower(trim($row[7])) : 'aktif';

            $data = [
                // Catatan: Pastikan urutan indeks $row[x] ini sesuai dengan urutan kolom di Excel/CSV
                'nim'           => $row[1] ?? null,
                'nama'          => $row[2] ?? null,
                'email'         => $row[3] ?? null,
                'prodi'         => $row[4] ?? null,
                'angkatan'      => $row[5] ?? null,
                'ipk'           => $row[6] ?? null,
                'status'        => $statusRaw,
                
                // Kolom pelengkap database
                'jenis_kelamin' => 'L', 
                'tanggal_lahir' => now()->format('Y-m-d'),
                'alamat'        => '-',
                'no_hp'         => '-',
                'foto'          => null,
            ];

            // 3. Validasi aturan data per baris
            $validator = Validator::make($data, [
                'nim'      => 'required|digits:10|unique:mahasiswa,nim', 
                'nama'     => 'required|string|max:255',
                'email'    => 'required|email|unique:mahasiswa,email',
                'prodi'    => 'required|in:Informatika,Sistem Informasi,Sains Data,Bisnis Digital',
                'angkatan' => 'required|integer|digits:4',
                'ipk'      => 'required|numeric|between:0.00,4.00',
                'status'   => 'required|in:aktif,cuti,lulus,do',
            ]);

            if ($validator->fails()) {
                $gagalRows[] = [
                    'baris'     => $rowNum,
                    'identitas' => $data['nama'] ?: ($data['nim'] ?: 'Tanpa Nama'),
                    'pesan'     => implode(', ', $validator->errors()->all())
                ];
            } else {
                Mahasiswa::create($data);
                $berhasil++;
            }
        }

        fclose($handle);

        // 4. Kirim laporan kembali ke index
        return redirect()->route('mahasiswa.index')->with([
            'import_status'   => true,
            'jumlah_berhasil' => $berhasil,
            'daftar_gagal'    => $gagalRows
        ]);
    }
}
