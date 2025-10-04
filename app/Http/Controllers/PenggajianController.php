<?php
namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Anggota;
use App\Models\KomponenGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenggajianController extends Controller
{
    // Menampilkan daftar data penggajian yang dikelompokkan berdasarkan Anggota
    public function index()
    {
        // Mengambil semua anggota dan memuat relasi penggajian mereka
        // Kemudian mengelompokkan data komponen gaji per anggota
        $anggota = Anggota::with(['penggajian.komponen'])
            ->orderBy('id_anggota', 'asc')
            ->get();

        return view('admin.penggajian.index', compact('anggota'));
    }

    // Menampilkan formulir untuk menambahkan/mengedit komponen gaji anggota
    public function create()
    {
        // Ambil data Anggota dan Komponen Gaji untuk dropdown
        $anggota = Anggota::select('id_anggota', 'nama_depan', 'nama_belakang')
                            ->orderBy('id_anggota', 'asc')
                            ->get();
        $komponen_gaji = KomponenGaji::orderBy('nama_komponen', 'asc')->get();

        // Kita akan menggunakan view yang mirip dengan KomponenGaji/create.blade.php
        return view('admin.penggajian.create', compact('anggota', 'komponen_gaji'));
    }

    // Mengambil daftar Komponen Gaji yang BELUM DITAMBAHKAN ke Anggota tertentu
    public function getUnassignedKomponenGaji($id_anggota)
    {
        // Ambil ID komponen gaji yang SUDAH dimiliki oleh anggota ini
        $assigned_ids = Penggajian::where('id_anggota', $id_anggota)
                                    ->pluck('id_komponen_gaji');
        
        // 2. Ambil semua komponen gaji yang ID-nya TIDAK ADA dalam daftar ID yang sudah dimiliki
        $unassigned_komponen = KomponenGaji::whereNotIn('id_komponen_gaji', $assigned_ids)
                                            ->orderBy('kategori', 'asc')
                                            ->orderBy('nama_komponen', 'asc')
                                            ->get();

        // Kembalikan data dalam bentuk JSON, dikelompokkan berdasarkan kategori
        return response()->json([
            'success' => true,
            'komponen_gaji' => $unassigned_komponen->groupBy('kategori')
        ]);
    }

    // Menyimpan data penggajian baru (menghubungkan anggota dengan komponen gaji)
    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|exists:anggota,id_anggota',
            'id_komponen_gaji' => 'required|array',
            'id_komponen_gaji.*' => 'exists:komponen_gaji,id_komponen_gaji', 
        ], [
            'id_anggota.required' => 'Anggota DPR wajib dipilih.',
            'id_anggota.exists' => 'Anggota DPR tidak valid.',
            'id_komponen_gaji.required' => 'Setidaknya satu Komponen Gaji wajib dipilih.',
            'id_komponen_gaji.*.exists' => 'Komponen Gaji yang dipilih tidak valid.',
        ]);

        $id_anggota = $request->id_anggota;
        $komponen_baru = $request->id_komponen_gaji;

        // Mendapatkan komponen gaji yang sudah ada untuk anggota ini
        $komponen_saat_ini = Penggajian::where('id_anggota', $id_anggota)
                                        ->pluck('id_komponen_gaji')
                                        ->toArray();

        // Hitung komponen yang perlu ditambahkan (yang baru, yang belum ada)
        $komponen_untuk_ditambahkan = array_diff($komponen_baru, $komponen_saat_ini);

        // Siapkan data untuk INSERT
        $data_insert = [];
        foreach ($komponen_untuk_ditambahkan as $id_komponen) {
            $data_insert[] = [
                'id_anggota' => $id_anggota,
                'id_komponen_gaji' => $id_komponen,
            ];
        }

        if (empty($data_insert)) {
            return redirect()->route('admin.penggajian.index')->with('error', 'Tidak ada komponen gaji baru yang ditambahkan karena sudah ada.');
        }

        try {
            // Gunakan transaksi untuk memastikan kedua operasi (check dan insert) berjalan aman
            DB::beginTransaction();

            // Masukkan data baru ke tabel penggajian
            Penggajian::insert($data_insert);

            DB::commit();

            $anggota_info = Anggota::find($id_anggota);
            $nama_anggota = $anggota_info ? $anggota_info->nama_depan . ' ' . $anggota_info->nama_belakang : 'Anggota ID ' . $id_anggota;
            
            return redirect()->route('admin.penggajian.index')->with('success', 'Berhasil menambahkan ' . count($data_insert) . ' komponen gaji baru untuk **' . $nama_anggota . '**.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            // \Log::error('Gagal menambahkan penggajian: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data penggajian. Terjadi kesalahan pada server.');
        }
    }

}