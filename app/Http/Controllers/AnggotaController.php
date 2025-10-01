<?php
// app/Http/Controllers/AnggotaController.php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    // Enum values dari database PostgreSQL (sesuai SQL file)
    private $jabatan_anggota = ['Ketua', 'Wakil Ketua', 'Anggota'];
    private $status_pernikahan = ['Kawin', 'Belum Kawin', 'Cerai Hidup', 'Cerai Mati'];

    // [ADMIN ONLY] Menampilkan daftar semua Anggota DPR (Read - Admin)
    public function index()
    {
        // Fitur Search
        $search = request('search');
        $anggota = Anggota::query()
            ->when($search, function($query, $search) {
                // Mencari di nama lengkap dan jabatan
                $query->where(DB::raw("CONCAT(COALESCE(gelar_depan, ''), ' ', nama_depan, ' ', nama_belakang, COALESCE(gelar_belakang, ''))"), 'ILIKE', "%{$search}%")
                      ->orWhere('jabatan', 'ILIKE', "%{$search}%")
                      ->orWhere('id_anggota', 'ILIKE', "%{$search}%");
            })
            ->orderBy('id_anggota', 'asc')
            ->paginate(10); // Menggunakan pagination
            
        return view('admin.anggota.index', compact('anggota', 'search'));
    }

    // [PUBLIC ONLY] Menampilkan daftar Anggota DPR (Read Only - Public)
    public function publicIndex()
    {
        // Fitur Search
        $search = request('search');
        $anggota = Anggota::query()
            ->when($search, function($query, $search) {
                // ILIKE untuk case-insensitive search di PostgreSQL pada nama dan jabatan
                $query->where(DB::raw("CONCAT(COALESCE(gelar_depan, ''), ' ', nama_depan, ' ', nama_belakang, COALESCE(gelar_belakang, ''))"), 'ILIKE', "%{$search}%")
                      ->orWhere('jabatan', 'ILIKE', "%{$search}%")
                      ->orWhere('id_anggota', 'ILIKE', "%{$search}%");
            })
            ->orderBy('id_anggota', 'asc')
            ->paginate(10); // Menggunakan pagination

        return view('public.anggota.index', compact('anggota', 'search'));
    }

    // [PUBLIC ONLY] Menampilkan detail Anggota DPR (Detail View - Public)
    // Digunakan juga oleh Admin di langkah selanjutnya untuk link detail
    public function show(Anggota $anggota)
    {
        return view('public.anggota.show', compact('anggota'));
    }

    // [ADMIN ONLY] Menampilkan form untuk menambah anggota baru
    public function create()
    {
        $jabatan = $this->jabatan_anggota;
        $status_pernikahan = $this->status_pernikahan;
        return view('admin.anggota.create', compact('jabatan', 'status_pernikahan'));
    }

    // [ADMIN ONLY] Menyimpan data anggota baru ke database (Create)
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_anggota' => 'required|numeric|unique:anggota,id_anggota',
            'nama_depan' => 'required|string|max:100',
            'nama_belakang' => 'required|string|max:100',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'jabatan' => 'required|in:' . implode(',', $this->jabatan_anggota),
            'status_pernikahan' => 'required|in:' . implode(',', $this->status_pernikahan),
        ], [
            'id_anggota.unique' => 'ID Anggota sudah terdaftar.',
            'jabatan.in' => 'Jabatan tidak valid.',
            'status_pernikahan.in' => 'Status pernikahan tidak valid.',
        ]);

        try {
            Anggota::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan data Anggota: ' . $e->getMessage());
        }

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota DPR berhasil ditambahkan!');
    }

    // [ADMIN ONLY] Menampilkan form untuk mengedit anggota (Edit)
    public function edit(Anggota $anggota)
    {
        $jabatan = $this->jabatan_anggota;
        $status_pernikahan = $this->status_pernikahan;
        return view('admin.anggota.edit', compact('anggota', 'jabatan', 'status_pernikahan'));
    }

    

    
}