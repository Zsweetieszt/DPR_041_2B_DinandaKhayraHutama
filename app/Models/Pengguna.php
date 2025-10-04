<?php
// app/Models/Pengguna.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    // Nama tabel di database
    protected $table = 'pengguna';
    
    // Primary key
    protected $primaryKey = 'id_pengguna';
    
    // Non-incrementing key
    public $incrementing = false;
    
    // Kolom yang dapat diisi
    protected $fillable = [
        'id_pengguna',
        'username',
        'password',
        'email',
        'nama_depan',
        'nama_belakang',
        'role',
    ];

    // Kolom yang disembunyikan (untuk keamanan)
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    // Override kolom autentikasi default
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // Menggabungkan nama depan dan belakang
    public function getFullNameAttribute()
    {
        return $this->nama_depan . ' ' . $this->nama_belakang;
    }
}