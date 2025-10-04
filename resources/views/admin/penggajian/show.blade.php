@extends('layouts.app')

@section('title', 'Detail Penggajian')

@section('content')
@php
    // Helper untuk format rupiah
    function format_rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-invoice-dollar me-2"></i> Detail Penggajian Anggota</h2>
    <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    {{-- Kolom Kiri: Informasi Anggota & Ringkasan Perhitungan --}}
    <div class="col-lg-5">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Informasi Anggota</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">ID Anggota</dt>
                    <dd class="col-sm-8">{{ $anggota->id_anggota }}</dd>

                    <dt class="col-sm-4">Nama Lengkap</dt>
                    {{-- Menggunakan accessor nama_lengkap yang sudah didefinisikan di Anggota Model --}}
                    <dd class="col-sm-8">{{ $anggota->nama_lengkap }}</dd> 

                    <dt class="col-sm-4">Jabatan</dt>
                    <dd class="col-sm-8"><span class="badge bg-danger">{{ $anggota->jabatan }}</span></dd>
                </dl>
                <hr>
                
                <h5 class="mt-4 mb-3">Ringkasan Perhitungan Gaji</h5>
                <dl class="row mb-0">
                    <dt class="col-sm-7 text-dark fw-bold">Total Gaji Pokok (Bulanan)</dt>
                    <dd class="col-sm-5 text-dark fw-bold text-end">{{ format_rupiah($calculation_summary['total_gaji_pokok_bulanan']) }}</dd>
                    
                    <dt class="col-sm-7">Total Tunjangan Melekat (Bulanan)</dt>
                    <dd class="col-sm-5 text-end">{{ format_rupiah($calculation_summary['total_tunjangan_melekat_bulanan']) }}</dd>
                    
                    <dt class="col-sm-7">Total Tunjangan Lain (Bulanan)</dt>
                    <dd class="col-sm-5 text-end">{{ format_rupiah($calculation_summary['total_tunjangan_lain_bulanan']) }}</dd>
                </dl>
                <hr class="my-2">
                <dl class="row mb-0">
                    <dt class="col-sm-7 text-success fw-bold">TOTAL PENGHASILAN BULANAN</dt>
                    <dd class="col-sm-5 text-success fw-bold text-end">{{ format_rupiah($calculation_summary['total_gaji_bulanan_final']) }}</dd>
                </dl>
                
                <p class="mt-4 mb-0 text-muted small">Catatan: Total Penghasilan Bulanan dihitung dari semua Komponen Gaji yang memiliki Satuan **'Bulan'**.</p>
                <dl class="row mt-2">
                    <dt class="col-sm-7 text-secondary">Total Komponen Periode/Fasilitas</dt>
                    <dd class="col-sm-5 text-secondary text-end">{{ format_rupiah($calculation_summary['total_tunjangan_periodik']) }}</dd>
                </dl>

            </div>
        </div>
    </div>
    
    {{-- Kolom Kanan: Detail Komponen Gaji --}}
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Daftar Komponen Gaji yang Dialokasikan ({{ $komponen_list->count() }} Item)</h5>
            </div>
            <div class="card-body">
                @if($komponen_list->isEmpty())
                    <div class="alert alert-warning text-center">Anggota ini belum dialokasikan komponen gaji apapun.</div>
                @else
                    @foreach ($grouped_komponen as $kategori => $list)
                        <h6 class="mt-3 mb-2 fw-bold text-danger">{{ $kategori }}</h6>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($list as $komponen)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $komponen->nama_komponen }} 
                                        <span class="badge bg-info text-dark ms-2">{{ $komponen->jabatan }}</span>
                                    </div>
                                    <span class="fw-bold text-end">
                                        {{ format_rupiah($komponen->nominal) }} / {{ $komponen->satuan }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection