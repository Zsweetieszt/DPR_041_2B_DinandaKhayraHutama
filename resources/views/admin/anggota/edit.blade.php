
@extends('layouts.app')

@section('title', 'Edit Anggota DPR')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Anggota DPR: {{ $anggota->nama_lengkap }} (ID: {{ $anggota->id_anggota }})</h4>
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
                
                {{-- PERBAIKAN: Menggunakan array asosiatif untuk parameter 'anggota' --}}
                <form id="edit-anggota-form" method="POST" action="{{ route('admin.anggota.update', ['anggota' => $anggota->id_anggota]) }}" novalidate>
                    @csrf
                    @method('PUT') 
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="id_anggota" class="form-label">ID Anggota</label>
                            <input type="text" id="id_anggota" class="form-control" value="{{ $anggota->id_anggota }}" disabled>
                            <small class="form-text text-muted">ID tidak dapat diubah.</small>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" id="jabatan" required>
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatan as $j)
                                    <option value="{{ $j }}" {{ old('jabatan', $anggota->jabatan) == $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                            @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="gelar_depan" class="form-label">Gelar Depan (Opsional)</label>
                            <input type="text" name="gelar_depan" class="form-control" id="gelar_depan" value="{{ old('gelar_depan', $anggota->gelar_depan) }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nama_depan" class="form-label">Nama Depan</label>
                            <input type="text" name="nama_depan" class="form-control @error('nama_depan') is-invalid @enderror" id="nama_depan" value="{{ old('nama_depan', $anggota->nama_depan) }}" required>
                            @error('nama_depan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="nama_belakang" class="form-label">Nama Belakang</label>
                            <input type="text" name="nama_belakang" class="form-control @error('nama_belakang') is-invalid @enderror" id="nama_belakang" value="{{ old('nama_belakang', $anggota->nama_belakang) }}" required>
                            @error('nama_belakang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="gelar_belakang" class="form-label">Gelar Belakang (Opsional)</label>
                            <input type="text" name="gelar_belakang" class="form-control" id="gelar_belakang" value="{{ old('gelar_belakang', $anggota->gelar_belakang) }}">
                        </div>
                        <div class="col-md-9 mb-3">
                            <label for="status_pernikahan" class="form-label">Status Pernikahan</label>
                            <select name="status_pernikahan" class="form-select @error('status_pernikahan') is-invalid @enderror" id="status_pernikahan" required>
                                <option value="">-- Pilih Status --</option>
                                @foreach($status_pernikahan as $s)
                                    <option value="{{ $s }}" {{ old('status_pernikahan', $anggota->status_pernikahan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                            @error('status_pernikahan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-warning me-2"><i class="fas fa-save me-2"></i> Update Data</button>
                    <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-anggota-form');

    // Menggunakan JavaScript untuk validasi dasar sebelum submit ke server
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous validation messages
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        const requiredFields = ['nama_depan', 'nama_belakang', 'jabatan', 'status_pernikahan'];

        requiredFields.forEach(fieldName => {
            const input = document.getElementById(fieldName);
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                let feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                let labelText = input.labels[0] ? input.labels[0].textContent : fieldName;
                feedback.textContent = `Kolom ${labelText.replace(' (Opsional)', '').toLowerCase()} wajib diisi.`;
                input.parentNode.appendChild(feedback);
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Scroll to the first invalid element
            form.querySelector('.is-invalid').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
</script>
@endsection