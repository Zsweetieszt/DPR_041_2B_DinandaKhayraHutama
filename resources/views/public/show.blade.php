@extends('layouts.app')
@section('title', 'Detail Anggota DPR: ' . $anggota->nama_lengkap)
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Detail Anggota DPR</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Nama Lengkap:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->nama_lengkap }}
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>ID Anggota:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->id_anggota }}
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Jabatan:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        <span class="badge bg-danger fs-6">{{ $anggota->jabatan }}</span>
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Status Pernikahan:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->status_pernikahan }}
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Jumlah Anak:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->jumlah_anak ?? 0 }} Anak
                    </div>
                    
                    {{-- Tambahkan di sini nanti: Rincian Gaji --}}
                </div>
                
                <hr>
                <a href="{{ route('public.anggota.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Anggota
                </a>
            </div>
        </div>
    </div>
</div>
@endsection