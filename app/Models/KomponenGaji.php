<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Anggota; 

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
        'jabatan', 
        'nominal',
        'satuan',
    ];

    protected $casts = [
        'nominal' => 'float',
    ];

    public function getNilaiTetapFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal, 2, ',', '.');
    }
    
    public function getNilaiTetapAttribute()
    {
        return $this->attributes['nominal'];
    }
    
    public function getJabatanKomponenAttribute()
    {
        return trim($this->attributes['jabatan'] ?? ''); 
    }
    
    public function getJabatanAttribute($value)
    {
        return trim($value ?? ''); 
    }

    public function anggota()
    {
        return $this->belongsToMany(
            Anggota::class, 
            'penggajian', 
            'id_komponen_gaji',
            'id_anggota'
        );
    }

}