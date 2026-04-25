@extends('layouts.app')
@section('title', 'Kelola Produk')
@section('styles')
<style>
.table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
th { padding: 14px 16px; text-align: left; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; border-bottom: 1px solid var(--border); }
td { padding: 14px 16px; font-size: 0.9rem; border-bottom: 1px solid var(--border); vertical-align: middle; }
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--glass); }
</style>
@endsection
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-size:1.8rem;font-weight:700">📦 Kelola Produk</h1>
        <p style="color:var(--text-muted);margin-top:4px">Tambah, edit, dan hapus produk</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="/admin" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
        <a href="/admin/products/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Produk</a>
    </div>
</div>

@if(session('success'))
<div style="background:rgba(34,197,94,0.1);border:1px solid var(--success);color:#22c55e;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:16px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="table-wrap">
    <table>
        <thead><tr>
            <th>Nama Produk</th><th>Harga</th><th>Stok</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        @forelse($products as $product)
        <tr>
            <td style="font-weight:600">{{ $product->name }}</td>
            <td style="color:var(--accent);font-weight:600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            <td>
                <span style="background:{{ $product->stock > 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }};color:{{ $product->stock > 0 ? '#22c55e' : '#ef4444' }};padding:3px 10px;border-radius:50px;font-size:0.82rem;font-weight:600">
                    {{ $product->stock }} unit
                </span>
            </td>
            <td>
                <div style="display:flex;gap:8px">
                    <a href="/admin/products/{{ $product->id }}/edit" class="btn btn-outline btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="/admin/products/{{ $product->id }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:40px">Belum ada produk</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top:16px">{{ $products->links() }}</div>
@endsection
