<?php
// app/Models/Anggota.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    public $incrementing = false;
    public $timestamps = false; // Matikan timestamps

    protected $fillable = [
        'id_anggota',
        'nama_depan',
        'nama_belakang',
        'gelar_depan',
        'gelar_belakang',
        'jabatan',
        'status_pernikahan',
    ];
    
    // Mutator untuk menggabungkan nama dan gelar
    public function getNamaLengkapAttribute()
    {
        $gelarDepan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $gelarBelakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        return $gelarDepan . $this->nama_depan . ' ' . $this->nama_belakang . $gelarBelakang;
    }

    // Relasi One-to-Many ke tabel Penggajian
    public function penggajian()
    {
        // Setiap Anggota memiliki banyak entri di tabel penggajian, diidentifikasi oleh id_anggota.
        return $this->hasMany(Penggajian::class, 'id_anggota', 'id_anggota');
    }
}