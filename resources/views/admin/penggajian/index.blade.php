@extends('layouts.app')

@section('title', 'Data Penggajian Anggota DPR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-money-check-alt me-2"></i> Kelola Data Penggajian</h2>
    <a href="{{ route('admin.penggajian.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Tambah Data Penggajian
    </a>
</div>

<div class="mb-4">
    <form action="{{ route('admin.penggajian.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama, ID, atau jabatan anggota..." value="{{ request('search') }}">
        <button class="btn btn-danger" type="submit">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.penggajian.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
        @endif
    </form>
</div>

@if($anggota->isEmpty())
    <div class="alert alert-info text-center">
        @if(request('search'))
            Tidak ditemukan data Anggota DPR yang sesuai dengan pencarian: "**{{ request('search') }}**".
        @else
            Belum ada data Anggota DPR.
        @endif
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-danger">
                <tr>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Jabatan</th>
                    <th>Jumlah Komponen Gaji</th>
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
                            <a href="{{ route('admin.penggajian.show', $item->id_anggota) }}" class="btn btn-sm btn-info text-white me-1" title="Lihat Detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.penggajian.edit', $item->id_anggota) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit me-1"></i>
                            </a>
                            <form action="{{ route('admin.penggajian.destroy', $item->id_anggota) }}" method="POST" class="d-inline form-delete-penggajian" data-nama="{{ $item->nama_depan }} {{ $item->nama_belakang }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
     <div class="mt-4 d-flex justify-content-center">
        {{ $anggota->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection