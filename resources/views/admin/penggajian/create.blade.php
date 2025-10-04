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

            {{-- Anggota DPR (Dropdown/Select) --}}
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
                
                {{-- Keterangan komponen gaji terpilih --}}
                <p class="text-muted small">Total komponen gaji terpilih: <strong id="selected-count">0</strong></p>
                
                {{-- Error validation untuk checkbox --}}
                @error('id_komponen_gaji')
                    <div class="alert alert-danger p-2">{{ $message }}</div>
                @enderror

                {{-- Kontainer Checkbox yang akan diupdate via AJAX --}}
                <div class="row" id="komponen-checkbox-container">
                    {{-- Pesan Awal / Placeholder --}}
                    <div class="col-12" id="initial-message">
                        <p class="text-center text-secondary">Silakan pilih Anggota DPR terlebih dahulu untuk menampilkan komponen gaji yang belum dialokasikan.</p>
                    </div>
                </div>
            </div>

            <hr>
            
            <button type="submit" class="btn btn-danger btn-lg me-2" id="submit-button" disabled>
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
    const anggotaSelect = document.getElementById('id_anggota');
    const checkboxContainer = document.getElementById('komponen-checkbox-container');
    const selectedCountSpan = document.getElementById('selected-count');
    const submitButton = document.getElementById('submit-button');
    
    // Fungsi untuk mengupdate counter dan status tombol Submit
    function updateSelectedCount() {
        const checkedCount = checkboxContainer.querySelectorAll('.komponen-checkbox:checked').length;
        selectedCountSpan.textContent = checkedCount;
        
        // Aktifkan tombol Submit hanya jika ada komponen yang dipilih DAN Anggota sudah dipilih
        submitButton.disabled = checkedCount === 0 || !anggotaSelect.value;
    }

    // Fungsi untuk merender checkbox dari data JSON
    function renderCheckboxes(groupedKomponen) {
        let html = '';
        
        for (const kategori in groupedKomponen) {
            if (groupedKomponen.hasOwnProperty(kategori)) {
                const list_komponen = groupedKomponen[kategori];
                
                html += `<div class="col-md-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white p-2">
                                    <h6 class="mb-0">${kategori}</h6>
                                </div>
                                <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">`;
                
                list_komponen.forEach(komponen => {
                    const formattedJumlah = new Intl.NumberFormat('id-ID').format(komponen.nominal);
                    
                    html += `<div class="form-check mb-1">
                                <input class="form-check-input komponen-checkbox" type="checkbox" 
                                    name="id_komponen_gaji[]" 
                                    value="${komponen.id_komponen_gaji}" 
                                    id="komponen_${komponen.id_komponen_gaji}">
                                <label class="form-check-label small" for="komponen_${komponen.id_komponen_gaji}">
                                    ${komponen.nama_komponen} 
                                    {{-- [PERBAIKAN 2]: Menggunakan 'satuan' (kolom DB yang menyimpan satuan) --}}
                                    (Rp ${formattedJumlah} / ${komponen.satuan})
                                </label>
                            </div>`;
                });

                html += `       </div>
                            </div>
                        </div>`;
            }
        }
        
        return html;
    }

    // Fungsi untuk mengambil data via AJAX
    function fetchUnassignedKomponen(idAnggota) {
        // Tampilkan loading state
        checkboxContainer.innerHTML = `<div class="col-12 text-center text-primary"><i class="fas fa-spinner fa-spin me-2"></i>Memuat Komponen Gaji...</div>`;
        selectedCountSpan.textContent = '0';
        submitButton.disabled = true; 

        // URL AJAX menggunakan rute yang sudah dibuat
        const url = `{{ route('admin.penggajian.get-komponen-unassigned', ['id_anggota' => 'ID_PLACEHOLDER']) }}`.replace('ID_PLACEHOLDER', idAnggota);

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && Object.keys(data.komponen_gaji).length > 0) {
                // Render daftar checkbox baru
                checkboxContainer.innerHTML = renderCheckboxes(data.komponen_gaji);
                
                // Tambahkan kembali listener ke checkbox yang baru
                checkboxContainer.querySelectorAll('.komponen-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectedCount);
                });
            } else {
                checkboxContainer.innerHTML = `<div class="col-12 text-center alert alert-success">Semua komponen gaji sudah dialokasikan untuk Anggota DPR ini!</div>`;
            }
            updateSelectedCount(); // Update counter setelah render
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            checkboxContainer.innerHTML = `<div class="col-12 text-center alert alert-danger">Gagal memuat data komponen gaji.</div>`;
            updateSelectedCount();
        });
    }

    // Event listener untuk dropdown Anggota DPR
    anggotaSelect.addEventListener('change', function() {
        const selectedId = this.value;
        if (selectedId) {
            fetchUnassignedKomponen(selectedId);
        } else {
            checkboxContainer.innerHTML = `<div class="col-12" id="initial-message"><p class="text-center text-secondary">Silakan pilih Anggota DPR terlebih dahulu untuk menampilkan komponen gaji yang belum dialokasikan.</p></div>`;
            updateSelectedCount();
        }
    });

    // Handle kondisi saat halaman dimuat
    if (anggotaSelect.value) {
         fetchUnassignedKomponen(anggotaSelect.value);
    } else {
        updateSelectedCount();
    }
});
</script>
@endsection