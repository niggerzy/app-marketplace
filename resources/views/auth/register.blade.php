@extends('layouts.app')
@section('title', 'Register')
@section('styles')
<style>
.auth-wrap { max-width: 420px; margin: 40px auto; }
.auth-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 6px; }
.auth-sub { color: var(--text-muted); margin-bottom: 28px; }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 6px; color: var(--text-muted); }
.error-box { background: rgba(239,68,68,0.1); border: 1px solid var(--danger); color: #ef4444; padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px; font-size: 0.9rem; }
</style>
@endsection
@section('content')
<div class="auth-wrap fade-up">
    <div class="card">
        <div class="auth-title">🚀 Buat Akun</div>
        <div class="auth-sub">Daftar dan mulai belanja</div>
        @if($errors->any())
            <div class="error-box"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif
        <form method="POST" action="/register">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="input-modern" placeholder="Nama kamu" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="input-modern" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="input-modern" placeholder="Min. 6 karakter" required>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="input-modern" placeholder="Ulangi password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>
        <p style="text-align:center;margin-top:20px;color:var(--text-muted);font-size:0.9rem">
            Sudah punya akun? <a href="/login" style="color:var(--primary);font-weight:600">Login</a>
        </p>
    </div>
</div>
@endsection
