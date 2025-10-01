@extends('layouts.app')

@section('title', 'Data Anggota DPR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-address-card me-2 text-primary"></i> Transparansi Data Anggota DPR</h2>
    <div class="info-badge">
        <span class="badge bg-primary fs-6">Akses Publik (Read Only)</span>
    </div>
</div>

{{-- Search Form --}}
<div class="mb-4">
    <form action="{{ route('public.anggota.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama, ID, atau jabatan anggota..." value="{{ $search }}">
        <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i> Cari
        </button>
        @if($search)
            <a href="{{ route('public.anggota.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
        @endif
    </form>
</div>

@if($anggota->isEmpty())
    <div class="alert alert-warning text-center">
        Tidak ditemukan data Anggota DPR yang sesuai dengan pencarian Anda.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Status Kawin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Table Looping --}}
                @foreach($anggota as $item)
                    <tr>
                        <td>{{ $item->id_anggota }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->status_pernikahan }}</td>
                        <td>
                            {{-- Link untuk Detail View --}}
                            <a href="{{ route('public.anggota.show', $item->id_anggota) }}" class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                <i class="fas fa-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $anggota->links() }}
    </div>
@endif

@endsection