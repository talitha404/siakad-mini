<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    /**
     * Menampilkan daftar dosen dengan fitur Search dan Filter Jabatan
     */
    public function index(Request $request)
    {
        // 1. Ambil parameter sorting dari request, berikan nilai default jika kosong
        $sortBy = $request->input('sort_by', 'nama'); // default urut berdasarkan nama
        $sortOrder = $request->input('sort_order', 'asc'); // default urut secara ascending (A-Z)

        // 2. Validasi kolom agar aman dari SQL Injection
        $validColumns = ['nidn', 'nama', 'jabatan_fungsional'];
        if (!in_array($sortBy, $validColumns)) {
            $sortBy = 'nama';
        }
        
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query = Dosen::query();

        // Fitur Pencarian berdasarkan NIDN atau Nama (Bawaan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nidn', 'like', "%{$search}%")
                ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Fitur Filter berdasarkan Jabatan Fungsional (Bawaan)
        if ($request->filled('jabatan')) {
            $query->where('jabatan_fungsional', $request->jabatan);
        }

        // 3. TERAPKAN SORTING: Ganti ->latest() dengan ->orderBy() dinamis
        $dosen = $query->orderBy($sortBy, $sortOrder)->paginate(10)->withQueryString();

        // 4. Kirim variabel $sortBy dan $sortOrder ke view untuk menyalakan indikator panah ▲/▼
        return view('dosen.index', compact('dosen', 'sortBy', 'sortOrder'));
    }

    /**
     * Menampilkan form tambah dosen
     */
    public function create()
    {
        return view('dosen.create');
    }

    /**
     * Menyimpan data dosen baru ke database
     */
    public function store(Request $request)
    {
        // Validasi sesuai instruksi (NIDN 15 digit, dll)
        $validated = $request->validate([
            'nidn' => 'required|string|size:15|unique:dosen,nidn',
            'nama' => 'required|string|max:100',
            'jabatan_fungsional' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nidn.size' => 'NIDN harus tepat 15 digit.',
            'nidn.unique' => 'NIDN sudah terdaftar.',
            'foto_profil.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('dosen', 'public');
            $validated['foto_profil'] = $path;
        }

        Dosen::create($validated);

        return redirect()->route('dosen.index')
            ->with('success', 'Data Dosen berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dosen
     */
    public function show(Dosen $dosen)
    {
        // Mengambil data dosen beserta daftar mata kuliah yang diampunya
        $dosen->load('mataKuliah');
        return view('dosen.show', compact('dosen'));
    }

    /**
     * Menampilkan halaman formulir edit data dosen (Tugas 1)
     */
    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    /**
     * Memperbarui data dosen di database
     */
    public function update(Request $request, Dosen $dosen)
    {
        // 1. Validasi Inputan Ketat (Sesuai dengan _form.blade.php)
        $validated = $request->validate([
            'nidn' => 'required|digits:15|unique:dosen,nidn,' . $dosen->id,
            'nama' => 'required|string|max:255',
            'jabatan_fungsional' => 'required|in:Asisten Ahli,Lektor,Kepala Lektor,Profesor',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Logika Update File Foto Profil
        if ($request->hasFile('foto_profil')) {
            // Jika dosen sudah punya foto lama di server, hapus agar storage tidak penuh
            if ($dosen->foto_profil) {
                Storage::disk('public')->delete($dosen->foto_profil);
            }
            // Simpan foto baru ke dalam folder 'storage/app/public/dosen'
            $path = $request->file('foto_profil')->store('dosen', 'public');
            $validated['foto_profil'] = $path;
        }

        // 3. Eksekusi pembaruan baris data ke database
        $dosen->update($validated);

        return redirect()->route('dosen.index')
            ->with('success', 'Data Dosen berhasil diperbarui.');
    }

    /**
     * Menghapus data dosen secara permanen (Delete - Tugas 1)
     */
    public function destroy(Dosen $dosen)
    {
        // 1. Amankan Storage: Hapus file berkas fisik foto jika ada di server
        if ($dosen->foto_profil) {
            Storage::disk('public')->delete($dosen->foto_profil);
        }

        // 2. Hapus data record dari tabel database
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', 'Data Dosen berhasil dihapus dari sistem.');
    } 
}
