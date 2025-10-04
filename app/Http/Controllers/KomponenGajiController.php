<?php
namespace App\Http\Controllers;

use App\Models\KomponenGaji;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KomponenGajiController extends Controller
{
    // Enum values dari database PostgreSQL
    private $kategori = ['Gaji Pokok', 'Tunjangan Melekat', 'Tunjangan Lain'];
    private $jabatan_komponen_values = ['Ketua', 'Wakil Ketua', 'Anggota', 'Semua']; 
    private $satuan = ['Bulan', 'Hari', 'Periode'];

    // Menampilkan daftar semua Komponen Gaji (Read)
    public function index()
    {
        $komponen = KomponenGaji::orderBy('id_komponen_gaji', 'asc')->get();
        
        $kategori = $this->kategori;
        $jabatan_komponen = $this->jabatan_komponen_values; 
        $satuan = $this->satuan;

        return view('admin.komponen_gaji.index', compact('komponen', 'kategori', 'jabatan_komponen', 'satuan'));
    }

    // Menampilkan form untuk menambah Komponen Gaji baru
    public function create()
    {
        $kategori = $this->kategori;
        $jabatan_komponen = $this->jabatan_komponen_values;
        $satuan = $this->satuan;

        return view('admin.komponen_gaji.create', compact('kategori', 'jabatan_komponen', 'satuan'));
    }

    // Menyimpan data Komponen Gaji baru ke database (Create)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_komponen_gaji' => 'required|numeric|unique:komponen_gaji,id_komponen_gaji',
            'nama_komponen' => 'required|string|max:100',
            'kategori' => 'required|in:' . implode(',', $this->kategori),
            'jabatan_komponen' => 'required|in:' . implode(',', $this->jabatan_komponen_values),
            'nilai_tetap' => 'required|numeric|min:0',
            'satuan' => 'required|in:' . implode(',', $this->satuan),
        ], [
            'id_komponen_gaji.unique' => 'ID Komponen Gaji sudah terdaftar.',
            'kategori.in' => 'Kategori komponen tidak valid.',
            'jabatan_komponen.required' => 'Kolom Jabatan Khusus wajib diisi.',
            'jabatan_komponen.in' => 'Jabatan khusus tidak valid.',
            'satuan.in' => 'Satuan komponen tidak valid.',
        ]);
        
        $dataToStore = [
            'id_komponen_gaji' => $validatedData['id_komponen_gaji'],
            'nama_komponen' => $validatedData['nama_komponen'],
            'kategori' => $validatedData['kategori'],
            'jabatan' => $validatedData['jabatan_komponen'],
            'nominal' => $validatedData['nilai_tetap'],
            'satuan' => $validatedData['satuan'],
        ];

        try {
            KomponenGaji::create($dataToStore);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan Komponen Gaji: ' . $e->getMessage());
        }

        return redirect()->route('admin.komponen.index')->with('success', 'Komponen Gaji berhasil ditambahkan!');
    }

    // Menampilkan form edit Komponen Gaji
    public function edit($id)
    {
        $komponen = KomponenGaji::findOrFail($id);
        $kategori = $this->kategori;
        $jabatan_komponen = $this->jabatan_komponen_values;
        $satuan = $this->satuan;

        return view('admin.komponen_gaji.edit', compact('komponen', 'kategori', 'jabatan_komponen', 'satuan'));
    }

    // Memperbarui data Komponen Gaji di database (Update)
    public function update(Request $request, $id)
    {
        $komponen = KomponenGaji::findOrFail($id);

        $validatedData = $request->validate([
            'nama_komponen' => 'required|string|max:100',
            'kategori' => 'required|in:' . implode(',', $this->kategori),
            'jabatan_komponen' => 'required|in:' . implode(',', $this->jabatan_komponen_values),
            'nilai_tetap' => 'required|numeric|min:0',
            'satuan' => 'required|in:' . implode(',', $this->satuan),
        ], [
            'kategori.in' => 'Kategori komponen tidak valid.',
            'jabatan_komponen.required' => 'Kolom Jabatan Khusus wajib diisi.',
            'jabatan_komponen.in' => 'Jabatan khusus tidak valid.',
            'nilai_tetap.required' => 'Kolom Nilai Tetap wajib diisi.',
            'satuan.in' => 'Satuan komponen tidak valid.',
        ]);

        $dataToUpdate = [
            'nama_komponen' => $validatedData['nama_komponen'],
            'kategori' => $validatedData['kategori'],
            'jabatan' => $validatedData['jabatan_komponen'],
            'nominal' => $validatedData['nilai_tetap'],
            'satuan' => $validatedData['satuan'],
        ];

        try {
            $komponen->update($dataToUpdate);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui Komponen Gaji: ' . $e->getMessage());
        }

        return redirect()->route('admin.komponen.index')->with('success', 'Komponen Gaji berhasil diperbarui!');
    }

    // Menghapus data Komponen Gaji dari database (Delete)
    public function destroy($id)
    {
        $komponen = KomponenGaji::find($id);

        if (!$komponen) {
            return back()->with('error', 'Komponen Gaji tidak ditemukan.');
        }

        try {
            $komponen->delete();
            return redirect()->route('admin.komponen.index')->with('success', 'Komponen Gaji "' . $komponen->nama_komponen . '" berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Error handling untuk relasi foreign key
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 23503) { 
                return back()->with('error', 'Gagal menghapus! Komponen Gaji "' . $komponen->nama_komponen . '" sudah terhubung dengan data penggajian, hapus data penggajian terlebih dahulu.');
            }
            return back()->with('error', 'Gagal menghapus Komponen Gaji: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}