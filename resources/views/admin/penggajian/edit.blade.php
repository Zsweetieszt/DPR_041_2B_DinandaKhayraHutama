@extends('layouts.app')

@section('title', 'Ubah Data Penggajian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-edit me-2"></i> Ubah Data Penggajian Anggota</h2>
    <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Atur Komponen Gaji untuk: **{{ $anggota->nama_depan }} {{ $anggota->nama_belakang }}** (ID: {{ $anggota->id_anggota }})</h5>
    </div>
    <div class="card-body">
        {{-- Form action ke rute update --}}
        <form action="{{ route('admin.penggajian.update', $anggota->id_anggota) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-bold">Anggota DPR</label>
                <p class="form-control-static border p-2 bg-light rounded">{{ $anggota->nama_depan }} {{ $anggota->nama_belakang }} (Jabatan: {{ $anggota->jabatan }})</p>
            </div>
            
            <div class="mb-4 p-3 border rounded">
                <label class="form-label fw-bold d-block mb-3">Atur Komponen Gaji <span class="text-danger">*</span></label>
                
                <p class="text-muted small">Total komponen gaji terpilih: <strong id="selected-count">0</strong></p>
                
                @error('id_komponen_gaji')
                    <div class="alert alert-danger p-2">{{ $message }}</div>
                @enderror

                @php
                    $grouped_komponen = $all_komponen_gaji->groupBy('kategori'); 
                    
                    $checked_ids = old('id_komponen_gaji', $assigned_ids);
                @endphp

                <div class="row" id="komponen-checkbox-container">
                    @forelse($grouped_komponen as $kategori => $list_komponen)
                        <div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white p-2">
                                    <h6 class="mb-0">{{ $kategori }}</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($list_komponen as $komponen)
                                        @php
                                            $isChecked = in_array($komponen->id_komponen_gaji, $checked_ids);
                                            $formattedJumlah = number_format($komponen->nominal, 0, ',', '.');
                                        @endphp
                                        <div class="form-check mb-1">
                                            <input class="form-check-input komponen-checkbox" type="checkbox" 
                                                name="id_komponen_gaji[]" 
                                                value="{{ $komponen->id_komponen_gaji }}" 
                                                id="komponen_{{ $komponen->id_komponen_gaji }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="komponen_{{ $komponen->id_komponen_gaji }}">
                                                {{ $komponen->nama_komponen }} 
                                                (Rp {{ $formattedJumlah }} / {{ $komponen->satuan }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center alert alert-warning">Tidak ada komponen gaji tersedia dalam sistem.</div>
                    @endforelse
                </div>
            </div>

            <hr>
            
            <button type="submit" class="btn btn-danger btn-lg me-2" id="submit-button">
                <i class="fas fa-save me-2"></i> Simpan Perubahan Penggajian
            </button>
            <a href="{{ route('admin.penggajian.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-undo me-2"></i> Batal
            </a>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxContainer = document.getElementById('komponen-checkbox-container');
    const selectedCountSpan = document.getElementById('selected-count');
    
    function updateSelectedCount() {
        const checkedCount = checkboxContainer.querySelectorAll('.komponen-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
    }

    checkboxContainer.querySelectorAll('.komponen-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    updateSelectedCount();
});
</script>
@endsection