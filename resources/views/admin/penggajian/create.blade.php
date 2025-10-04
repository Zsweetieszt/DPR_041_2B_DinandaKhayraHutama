@extends('layouts.app')

@section('title', 'Tambah Data Penggajian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-plus me-2"></i> Tambah Data Penggajian Anggota</h2>
    <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Formulir Alokasi Komponen Gaji</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.penggajian.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="id_anggota" class="form-label fw-bold">Pilih Anggota DPR <span class="text-danger">*</span></label>
                <select class="form-select @error('id_anggota') is-invalid @enderror" id="id_anggota" name="id_anggota" required>
                    <option value="" disabled selected>-- Pilih Anggota DPR --</option>
                    @foreach($anggota as $item)
                        <option value="{{ $item->id_anggota }}" {{ old('id_anggota') == $item->id_anggota ? 'selected' : '' }}>
                            {{ $item->id_anggota }} - {{ $item->nama_depan }} {{ $item->nama_belakang }} ({{ $item->jabatan }})
                        </option>
                    @endforeach
                </select>
                @error('id_anggota')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            {{-- Komponen Gaji (Checkbox Group) --}}
            <div class="mb-4 p-3 border rounded">
                <label class="form-label fw-bold d-block mb-3">Pilih Komponen Gaji yang akan Ditambahkan <span class="text-danger">*</span></label>
                
                {{-- COUNTER --}}
                <p class="text-muted small">Total komponen gaji terpilih: <strong id="selected-count">0</strong></p>
                
                {{-- Error validation untuk checkbox --}}
                @error('id_komponen_gaji')
                    <div class="alert alert-danger p-2">{{ $message }}</div>
                @enderror

                @php
                    $grouped_komponen = $komponen_gaji->groupBy('kategori'); 
                    $old_komponen = old('id_komponen_gaji', []);
                @endphp

                <div class="row" id="komponen-checkbox-container">
                    @foreach($grouped_komponen as $kategori => $list_komponen)
                        <div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white p-2">
                                    <h6 class="mb-0">{{ $kategori }}</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($list_komponen as $komponen)
                                        @php
                                            $isChecked = in_array($komponen->id_komponen_gaji, $old_komponen);
                                        @endphp
                                        <div class="form-check mb-1">
                                            <input class="form-check-input komponen-checkbox" type="checkbox" 
                                                name="id_komponen_gaji[]" 
                                                value="{{ $komponen->id_komponen_gaji }}" 
                                                id="komponen_{{ $komponen->id_komponen_gaji }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="komponen_{{ $komponen->id_komponen_gaji }}">
                                                {{ $komponen->nama_komponen }} 
                                                ({{ $komponen->nilai_tetap_formatted }} / {{ $komponen->satuan }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> 

            <hr>
            
            <button type="submit" class="btn btn-danger btn-lg me-2">
                <i class="fas fa-save me-2"></i> Simpan Data Penggajian
            </button>
            <a href="{{ route('admin.penggajian.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-undo me-2"></i> Batal
            </a>

        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.komponen-checkbox');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateSelectedCount() {
        // Hitung berapa banyak checkbox yang dicentang
        const checkedCount = document.querySelectorAll('.komponen-checkbox:checked').length;
        
        // Update tampilan
        selectedCountSpan.textContent = checkedCount;
    }

    // Tambahkan event listener untuk setiap checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Panggil sekali saat halaman dimuat untuk menampilkan nilai lama (jika ada error validasi)
    updateSelectedCount();
});
</script>
@endsection