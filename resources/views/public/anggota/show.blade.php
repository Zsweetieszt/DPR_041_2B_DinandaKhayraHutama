@extends('layouts.app')

@section('title', 'Detail Anggota DPR: ' . $anggota->nama_lengkap)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i> Detail Anggota DPR</h4>
            </div>
            <div class="card-body">
                <h3 class="card-title text-center mb-4">{{ $anggota->nama_lengkap }}</h3>
                <hr>
                <div class="row">
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>ID Anggota:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        <span class="badge bg-secondary">{{ $anggota->id_anggota }}</span>
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Jabatan:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        <span class="badge bg-danger fs-6">{{ $anggota->jabatan }}</span>
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Gelar Depan:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->gelar_depan ?? '-' }}
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Gelar Belakang:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->gelar_belakang ?? '-' }}
                    </div>
                    
                    <div class="col-md-5 text-md-end mb-3">
                        <strong>Status Pernikahan:</strong>
                    </div>
                    <div class="col-md-7 mb-3">
                        {{ $anggota->status_pernikahan }}
                    </div>
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