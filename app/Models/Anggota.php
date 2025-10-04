<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KomponenGaji; 

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    public $incrementing = false;
    public $timestamps = false; 

    protected $fillable = [
        'id_anggota',
        'nama_depan',
        'nama_belakang',
        'gelar_depan',
        'gelar_belakang',
        'jabatan',
        'status_pernikahan',
    ];

    public function getNamaLengkapAttribute()
    {
        $gelarDepan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $gelarBelakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        return $gelarDepan . $this->nama_depan . ' ' . $this->nama_belakang . $gelarBelakang;
    }
    
    // Relasi One-to-Many ke tabel Penggajian
    public function penggajian()
    {
        return $this->hasMany(Penggajian::class, 'id_anggota', 'id_anggota');
    }

    // Relasi Many-to-Many ke KomponenGaji
    public function komponenGaji()
    {
        return $this->belongsToMany(
            KomponenGaji::class,    
            'penggajian',           
            'id_anggota',          
            'id_komponen_gaji'    
        );
    }
}