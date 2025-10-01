@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-5">
    <div class="p-5 text-center bg-light rounded-3 shadow-sm" style="background-color: #fff3f3 !important;">
        <h1 class="text-danger"><i class="fas fa-user-shield me-2"></i> Welcome, {{ Auth::user()->full_name }}!</h1>
        <p class="fs-4 mt-3">Anda login sebagai **Admin**.</p>
        <p class="lead">Gunakan menu navigasi untuk mengelola data Anggota DPR, Komponen Gaji, dan Data Penggajian.</p>
        <hr class="my-4">
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="#" class="btn btn-danger btn-lg px-4 me-sm-3">
                <i class="fas fa-users-cog me-2"></i> Kelola Anggota
            </a>
            <a href="#" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-dollar-sign me-2"></i> Kelola Gaji
            </a>
        </div>
    </div>
</div>
@endsection