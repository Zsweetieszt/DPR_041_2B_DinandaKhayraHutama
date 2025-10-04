<?php
namespace App\Http\Controllers;

use App\Models\KomponenGaji;
use Illuminate\Http\Request;

class KomponenGajiController extends Controller
{
    // Enum values dari database PostgreSQL
    private $kategori = ['Gaji Pokok', 'Tunjangan Melekat', 'Tunjangan Lain'];
    private $jabatan_komponen = ['Ketua', 'Wakil Ketua', 'Anggota', 'Semua']; 
    private $satuan = ['Bulan', 'Hari', 'Periode'];

    // Menampilkan daftar semua Komponen Gaji (Read)
    public function index()
    {
        $komponen = KomponenGaji::orderBy('id_komponen_gaji', 'asc')->get();
        $kategori = $this->kategori;
        $jabatan_komponen = $this->jabatan_komponen; 
        $satuan = $this->satuan;

        return view('admin.komponen_gaji.index', compact('komponen', 'kategori', 'jabatan_komponen', 'satuan'));
    }

    // Menampilkan form untuk menambah Komponen Gaji baru
    public function create()
    {
        $kategori = $this->kategori;
        $jabatan_komponen = $this->jabatan_komponen;
        $satuan = $this->satuan;

        return view('admin.komponen_gaji.create', compact('kategori', 'jabatan_komponen', 'satuan'));
    }

    // Menyimpan data Komponen Gaji baru ke database (Create)
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_komponen_gaji' => 'required|numeric|unique:komponen_gaji,id_komponen_gaji',
            'nama_komponen' => 'required|string|max:100',
            'kategori' => 'required|in:' . implode(',', $this->kategori),
            'jabatan_komponen' => 'required|in:' . implode(',', $this->jabatan_komponen), 
            'nilai_tetap' => 'required|numeric|min:0',
            'satuan' => 'required|in:' . implode(',', $this->satuan),
        ], [
            'id_komponen_gaji.unique' => 'ID Komponen Gaji sudah terdaftar.',
            'kategori.in' => 'Kategori komponen tidak valid.',
            'jabatan_komponen.in' => 'Jabatan khusus tidak valid.',
            'satuan.in' => 'Satuan komponen tidak valid.',
        ]);

        try {
            KomponenGaji::create($data);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan Komponen Gaji: ' . $e->getMessage());
        }

        return redirect()->route('admin.komponen.index')->with('success', 'Komponen Gaji berhasil ditambahkan!');
    }
}