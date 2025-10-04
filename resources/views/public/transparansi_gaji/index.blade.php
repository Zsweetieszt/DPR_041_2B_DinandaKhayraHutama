@extends('layouts.app')

@section('title', 'Transparansi Gaji DPR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-eye me-2"></i> Transparansi Gaji Anggota DPR</h2>
</div>

@if($anggota->isEmpty())
    <div class="alert alert-warning text-center">
        Belum ada data alokasi gaji yang tersedia untuk ditampilkan.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-danger">
                <tr>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Jabatan</th>
                    <th>Komponen Dialokasikan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota as $item)
                    <tr>
                        <td>{{ $item->id_anggota }}</td>
                        <td>{{ $item->nama_depan }} {{ $item->nama_belakang }}</td>
                        <td><span class="badge bg-secondary">{{ $item->jabatan }}</span></td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $item->penggajian->count() }} Komponen</span>
                        </td>
                        <td>
                            <a href="{{ route('public.transparansi.show', $item->id_anggota) }}" class="btn btn-sm btn-danger text-white me-1" title="Lihat Detail Gaji">
                                <i class="fas fa-search"></i> Lihat Detail Gaji
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection