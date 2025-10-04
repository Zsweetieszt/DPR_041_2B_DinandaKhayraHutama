<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    // Tabel relasi ini tidak memiliki primary key tunggal/incrementing, dan tidak memiliki timestamps.
    protected $table = 'penggajian';
    public $incrementing = false;
    public $timestamps = false; 

    // Kolom-kolom yang ada di tabel 'penggajian'
    protected $fillable = [
        'id_komponen_gaji',
        'id_anggota',
    ];

    // Relasi ke tabel anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    // Relasi ke tabel komponen_gaji
    public function komponen()
    {
        return $this->belongsTo(KomponenGaji::class, 'id_komponen_gaji', 'id_komponen_gaji');
    }
}