@extends('layouts.app')

@section('title', 'Public Dashboard')

@section('content')
<div class="container mt-5">
    <div class="p-5 text-center bg-light rounded-3 shadow-sm">
        <h1 class="text-primary"><i class="fas fa-bullhorn me-2"></i> Transparansi Gaji DPR</h1>
        <p class="fs-4 mt-3">Anda login sebagai **Public/Client**.</p>
        <p class="lead">Aplikasi ini menyajikan data anggota DPR dan informasi penggajian secara transparan (Read Only).</p>
        <hr class="my-4">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="#" class="btn btn-primary btn-lg px-4 me-sm-3">
                <i class="fas fa-address-card me-2"></i> Lihat Anggota DPR
            </a>
            <a href="#" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-calculator me-2"></i> Lihat Data Gaji
            </a>
        </div>
    </div>
</div>
@endsection