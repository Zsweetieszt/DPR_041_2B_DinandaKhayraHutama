
@extends('layouts.app')

@section('title', 'Tambah Komponen Gaji Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="fas fa-plus me-2"></i> Tambah Komponen Gaji Baru</h4>
            </div>
            <div class="card-body">
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form id="create-komponen-form" method="POST" action="{{ route('admin.komponen.store') }}" novalidate>
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="id_komponen_gaji" class="form-label">ID Komponen Gaji</label>
                            <input type="text" name="id_komponen_gaji" class="form-control @error('id_komponen_gaji') is-invalid @enderror" id="id_komponen_gaji" value="{{ old('id_komponen_gaji') }}" required>
                            @error('id_komponen_gaji')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nama_komponen" class="form-label">Nama Komponen</label>
                            <input type="text" name="nama_komponen" class="form-control @error('nama_komponen') is-invalid @enderror" id="nama_komponen" value="{{ old('nama_komponen') }}" required>
                            @error('nama_komponen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" id="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k }}" {{ old('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="jabatan_komponen" class="form-label">Jabatan Khusus</label>
                            <select name="jabatan_komponen" class="form-select @error('jabatan_komponen') is-invalid @enderror" id="jabatan_komponen" required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan_komponen as $j)
                                    <option value="{{ $j }}" {{ old('jabatan_komponen') == $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                            @error('jabatan_komponen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" id="satuan" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuan as $s)
                                    <option value="{{ $s }}" {{ old('satuan') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nilai_tetap" class="form-label">Nilai Tetap (Rp)</label>
                        <input type="number" step="0.01" name="nilai_tetap" class="form-control @error('nilai_tetap') is-invalid @enderror" id="nilai_tetap" value="{{ old('nilai_tetap') }}" required min="0">
                        @error('nilai_tetap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Masukkan angka, contoh: 1500000.00</small>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-danger me-2"><i class="fas fa-save me-2"></i> Simpan Komponen</button>
                    <a href="{{ route('admin.komponen.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('create-komponen-form');

    // Menggunakan JavaScript untuk validasi dasar sebelum submit ke server
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous validation messages
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        const requiredFields = ['id_komponen_gaji', 'nama_komponen', 'kategori', 'jabatan_komponen', 'satuan', 'nilai_tetap'];

        requiredFields.forEach(fieldName => {
            const input = document.getElementById(fieldName);
            if (!input || !input.value.trim()) {
                input.classList.add('is-invalid');
                let feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                let labelText = input.labels[0] ? input.labels[0].textContent : fieldName;
                feedback.textContent = `Kolom ${labelText.replace(' (Rp)', '').toLowerCase()} wajib diisi.`;
                input.parentNode.appendChild(feedback);
                isValid = false;
            }
        });
        
        // Validasi khusus Nilai Tetap
        const nilaiTetapInput = document.getElementById('nilai_tetap');
        if (nilaiTetapInput) {
            const val = parseFloat(nilaiTetapInput.value);
            if (isNaN(val) || val < 0) {
                nilaiTetapInput.classList.add('is-invalid');
                let feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Nilai harus angka positif.';
                nilaiTetapInput.parentNode.appendChild(feedback);
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll to the first invalid element
            form.querySelector('.is-invalid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endsection