@extends('layouts.app')

@section('title', 'Ubah Komponen Gaji')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Ubah Komponen Gaji</h4>
            </div>
            <div class="card-body">
                
                {{-- Menampilkan pesan sukses/error jika ada dari Controller --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                {{-- Menampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="edit-komponen-form" method="POST" action="{{ route('admin.komponen.update', $komponen->id_komponen_gaji) }}" novalidate>
                    @csrf
                    @method('PUT') 

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="id_komponen_gaji" class="form-label">ID Komponen Gaji</label>
                            <input type="text" name="id_komponen_gaji" class="form-control" 
                                id="id_komponen_gaji" value="{{ old('id_komponen_gaji', $komponen->id_komponen_gaji) }}" readonly>
                            <small class="form-text text-muted">ID tidak dapat diubah.</small>
                            @error('id_komponen_gaji')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nama_komponen" class="form-label">Nama Komponen</label>
                            <input type="text" name="nama_komponen" class="form-control @error('nama_komponen') is-invalid @enderror" 
                                id="nama_komponen" value="{{ old('nama_komponen', $komponen->nama_komponen) }}" required>
                            @error('nama_komponen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" id="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $k)
                                    <option value="{{ $k }}" {{ old('kategori', $komponen->kategori) == $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="jabatan_komponen" class="form-label">Jabatan Khusus</label>
                            <select name="jabatan_komponen" class="form-select @error('jabatan_komponen') is-invalid @enderror" id="jabatan_komponen" required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan_komponen as $j)
                                    <option value="{{ $j }}" {{ old('jabatan_komponen', $komponen->jabatan_komponen) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                            @error('jabatan_komponen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" id="satuan" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuan as $s)
                                    <option value="{{ $s }}" {{ old('satuan', $komponen->satuan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nilai_tetap" class="form-label">Nilai Tetap (Rp)</label>
                        <input type="number" step="0.01" name="nilai_tetap" class="form-control @error('nilai_tetap') is-invalid @enderror" 
                            id="nilai_tetap" value="{{ old('nilai_tetap', $komponen->nilai_tetap) }}" required min="0">
                        @error('nilai_tetap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="form-text text-muted">Masukkan angka, contoh: 1500000.00</small>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-danger me-2"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
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
    // Sesuaikan ID form
    const form = document.getElementById('edit-komponen-form');

    // Menggunakan JavaScript untuk validasi dasar sebelum submit ke server
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous validation messages
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        // Hapus feedback yang dibuat secara manual oleh JS
        form.querySelectorAll('.invalid-feedback.d-block').forEach(el => el.remove());

        // ID Komponen Gaji dikecualikan dari pemeriksaan required karena readonly
        const requiredFields = ['nama_komponen', 'kategori', 'jabatan_komponen', 'satuan', 'nilai_tetap']; 

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
                // Pastikan class is-invalid ditambahkan
                nilaiTetapInput.classList.add('is-invalid');
                
                // Tambahkan pesan feedback jika belum ada (jika tidak ter-handle oleh Laravel error)
                if (!nilaiTetapInput.nextElementSibling || !nilaiTetapInput.nextElementSibling.classList.contains('invalid-feedback')) {
                    let feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Nilai harus angka positif.';
                    nilaiTetapInput.parentNode.appendChild(feedback);
                }
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            form.querySelector('.is-invalid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endsection