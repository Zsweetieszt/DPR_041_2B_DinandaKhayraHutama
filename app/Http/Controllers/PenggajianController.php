<?php
namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\Anggota;
use Illuminate\Http\Request;

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

}