@extends('layouts.app')

@section('title', 'Kelola Anggota DPR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users-cog me-2"></i> Kelola Data Anggota DPR</h2>
    <a href="{{ route('admin.anggota.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Tambah Anggota Baru
    </a>
</div>

@if($anggota->isEmpty())
    <div class="alert alert-info text-center">
        Belum ada data Anggota DPR. Silakan tambahkan data baru.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-danger">
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Status Kawin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota as $item)
                    <tr>
                        <td>{{ $item->id_anggota }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->status_pernikahan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection