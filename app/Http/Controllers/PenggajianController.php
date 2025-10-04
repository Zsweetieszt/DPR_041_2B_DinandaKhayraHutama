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
        $anggota = Anggota::with(['penggajian.komponen'])
            ->orderBy('id_anggota', 'asc')
            ->get();

        return view('admin.penggajian.index', compact('anggota'));
    }

    // Menampilkan formulir untuk menambahkan/mengedit komponen gaji anggota
    public function create()
    {
        $anggota = Anggota::select('id_anggota', 'nama_depan', 'nama_belakang')
                            ->orderBy('id_anggota', 'asc')
                            ->get();
        $komponen_gaji = KomponenGaji::orderBy('nama_komponen', 'asc')->get();
        return view('admin.penggajian.create', compact('anggota', 'komponen_gaji'));
    }

    // Mengambil daftar Komponen Gaji yang BELUM DITAMBAHKAN ke Anggota tertentu
    public function getUnassignedKomponenGaji($id_anggota)
    {
        $assigned_ids = Penggajian::where('id_anggota', $id_anggota)
                                    ->pluck('id_komponen_gaji');
        
        $unassigned_komponen = KomponenGaji::whereNotIn('id_komponen_gaji', $assigned_ids)
                                            ->orderBy('kategori', 'asc')
                                            ->orderBy('nama_komponen', 'asc')
                                            ->get();
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

        $komponen_saat_ini = Penggajian::where('id_anggota', $id_anggota)
                                        ->pluck('id_komponen_gaji')
                                        ->toArray();

        $komponen_untuk_ditambahkan = array_diff($komponen_baru, $komponen_saat_ini);

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
            DB::beginTransaction();

            Penggajian::insert($data_insert);

            DB::commit();

            $anggota_info = Anggota::find($id_anggota);
            $nama_anggota = $anggota_info ? $anggota_info->nama_depan . ' ' . $anggota_info->nama_belakang : 'Anggota ID ' . $id_anggota;
            
            return redirect()->route('admin.penggajian.index')->with('success', 'Berhasil menambahkan ' . count($data_insert) . ' komponen gaji baru untuk **' . $nama_anggota . '**.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data penggajian. Terjadi kesalahan pada server.');
        }
    }

    // Menampilkan formulir untuk mengedit data penggajian (mengatur komponen gaji anggota)
    public function edit($id_anggota)
    {
        $anggota = Anggota::select('id_anggota', 'nama_depan', 'nama_belakang', 'jabatan')
                            // MENGGUNAKAN RELASI komponenGaji() untuk mendapatkan assigned IDs
                            ->with('komponenGaji') 
                            ->where('id_anggota', $id_anggota)
                            ->firstOrFail();

        $all_komponen_gaji = KomponenGaji::orderBy('kategori', 'asc')
                                        ->orderBy('nama_komponen', 'asc')
                                        ->get();

        $assigned_ids = $anggota->komponenGaji->pluck('id_komponen_gaji')->toArray();

        return view('admin.penggajian.edit', compact('anggota', 'all_komponen_gaji', 'assigned_ids'));
    }

    // Menyimpan perubahan data penggajian (sinkronisasi komponen gaji anggota)
    public function update(Request $request, $id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);

        $request->validate([
            'id_komponen_gaji' => 'nullable|array',
            'id_komponen_gaji.*' => 'exists:komponen_gaji,id_komponen_gaji', 
        ], [
            'id_komponen_gaji.*.exists' => 'Komponen Gaji yang dipilih tidak valid.',
        ]);

        $komponen_terpilih = $request->id_komponen_gaji ?? [];

        try {
            $result = $anggota->komponenGaji()->sync($komponen_terpilih);
            
            $added = count($result['attached']);
            $removed = count($result['detached']);
            $nama_anggota = $anggota->nama_depan . ' ' . $anggota->nama_belakang;

            if ($added === 0 && $removed === 0) {
                 return redirect()->route('admin.penggajian.index')->with('info', 'Tidak ada perubahan komponen gaji untuk **' . $nama_anggota . '**.');
            }
            
            $message = "Berhasil memperbarui komponen gaji untuk **{$nama_anggota}**.";
            $message .= " ({$added} ditambahkan, {$removed} dihapus).";

            return redirect()->route('admin.penggajian.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data penggajian. Terjadi kesalahan pada server. Detail: ' . $e->getMessage());
        }
    }

    // Menghapus data penggajian (menghapus semua alokasi komponen gaji) untuk seorang Anggota
    public function destroy($id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);
        $nama_anggota = $anggota->nama_depan . ' ' . $anggota->nama_belakang;

        try {
            $anggota->komponenGaji()->detach(); 

            return redirect()->route('admin.penggajian.index')->with('success', 'Semua data penggajian untuk **' . $nama_anggota . '** berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data penggajian. Terjadi kesalahan pada server. Detail: ' . $e->getMessage());
        }
    }
}