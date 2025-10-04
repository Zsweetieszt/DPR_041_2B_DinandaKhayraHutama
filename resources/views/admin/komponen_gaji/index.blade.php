@extends('layouts.app')

@section('title', 'Daftar Komponen Gaji')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-list-alt me-2"></i> Kelola Komponen Gaji</h2>
    <a href="{{ route('admin.komponen.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Tambah Komponen Baru
    </a>
</div>

<div class="mb-4">
    <form action="{{ route('admin.komponen.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari ID, nama, kategori, jabatan, atau satuan..." value="{{ request('search') }}">
        <button class="btn btn-danger" type="submit">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.komponen.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
        @endif
    </form>
</div>

@if($komponen->isEmpty())
    <div class="alert alert-info text-center">
        @if(request('search'))
            Tidak ditemukan data Komponen Gaji yang sesuai dengan pencarian: "**{{ request('search') }}**".
        @else
            Belum ada data Komponen Gaji. Silakan tambahkan data baru.
        @endif
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Nama Komponen</th>
                    <th>Kategori</th>
                    <th>Jabatan Komponen</th>
                    <th>Nilai Tetap</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($komponen as $item)
                    <tr>
                        <td>{{ $item->id_komponen_gaji }}</td>
                        <td>{{ $item->nama_komponen }}</td>
                        <td><span class="badge bg-secondary">{{ $item->kategori }}</span></td>
                        <td><span class="badge bg-info text-dark">{{ $item->jabatan_komponen }}</span></td> 
                        <td>{{ $item->nilai_tetap_formatted }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>
                            <a href="{{ route('admin.komponen.edit', $item->id_komponen_gaji) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.komponen.destroy', $item->id_komponen_gaji) }}" method="POST" class="d-inline form-delete-komponen" data-nama="{{ $item->nama_komponen }}">
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
        {{ $komponen->links() }}
    </div>
@endif

@endsection