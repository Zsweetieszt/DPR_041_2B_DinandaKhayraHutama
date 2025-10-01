<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenGaji extends Model
{
    protected $table = 'komponen_gaji';
    protected $primaryKey = 'id_komponen_gaji';
    public $incrementing = false;
    public $timestamps = false; 

    protected $fillable = [
        'id_komponen_gaji',
        'nama_komponen',
        'kategori',
        'jabatan_komponen', 
        'nilai_tetap',
        'satuan',
    ];

    protected $casts = [
        'nilai_tetap' => 'float',
    ];

    public function getNilaiTetapFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_tetap, 2, ',', '.');
    }
    
    // Memastikan nilai ENUM di-trim (jika ada whitespace tak terlihat)
    public function getJabatanKomponenAttribute($value)
    {
        return trim($value ?? ''); 
    }
}