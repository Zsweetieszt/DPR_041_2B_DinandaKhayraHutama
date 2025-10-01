@extends('layouts.app')

@section('title', 'Kelola Anggota DPR')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users-cog me-2"></i> Kelola Data Anggota DPR</h2>
    <a href="{{ route('admin.anggota.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-2"></i> Tambah Anggota Baru
    </a>
</div>

{{-- Search Form --}}
<div class="mb-4">
    <form action="{{ route('admin.anggota.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari nama, ID, atau jabatan anggota..." value="{{ request('search') }}">
        <button class="btn btn-danger" type="submit">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.anggota.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
        @endif
    </form>
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota as $item)
                    <tr>
                        <td>{{ $item->id_anggota }}</td>
                        <td>{{ $item->nama_lengkap }}</td>
                        <td>{{ $item->jabatan }}</td>
                        <td>{{ $item->status_pernikahan }}</td>
                        <td>
                            {{-- PERBAIKAN 1: Menggunakan array asosiatif untuk parameter 'anggota' --}}
                            <a href="{{ route('admin.anggota.edit', ['anggota' => $item->id_anggota]) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            {{-- PERBAIKAN 2: Menggunakan array asosiatif untuk parameter 'anggota' di form destroy --}}
                            <form action="{{ route('admin.anggota.destroy', ['anggota' => $item->id_anggota]) }}" method="POST" class="d-inline form-delete-anggota" data-nama="{{ $item->nama_lengkap }}">
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
        {{ $anggota->links() }}
    </div>
@endif

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Delete confirmation
    document.querySelectorAll('.form-delete-anggota').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const namaAnggota = this.getAttribute('data-nama');
            
            // Delete confirmation dialog
            const confirmed = confirm(`Apakah Anda yakin ingin menghapus Anggota DPR:\n"${namaAnggota}"?`);
            
            if (confirmed) {
                this.submit();
            }
        });
    });
});
</script>
@endsection