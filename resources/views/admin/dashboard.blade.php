@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('styles')
<style>
.stat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }
.stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 20px; text-align: center; transition: border-color 0.2s; }
.stat-card:hover { border-color: var(--primary); }
.stat-num { font-size: 2rem; font-weight: 800; background: linear-gradient(to right, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.stat-label { color: var(--text-muted); font-size: 0.85rem; margin-top: 4px; }
.table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
table { width: 100%; border-collapse: collapse; }
th { padding: 14px 16px; text-align: left; font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); letter-spacing: 0.5px; border-bottom: 1px solid var(--border); }
td { padding: 14px 16px; font-size: 0.9rem; border-bottom: 1px solid var(--border); }
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--glass); }
.quick-links { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
</style>
@endsection
@section('content')
<div class="fade-up" style="margin-bottom:24px">
    <h1 style="font-size:1.8rem;font-weight:700">🛡️ Admin Dashboard</h1>
    <p style="color:var(--text-muted);margin-top:4px">Kelola toko kamu dari sini</p>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-num">{{ $stats['total_orders'] }}</div>
        <div class="stat-label"><i class="fas fa-shopping-bag"></i> Total Order</div>
    </div>
    <div class="stat-card">
        <div class="stat-num" style="-webkit-text-fill-color:#eab308">{{ $stats['pending'] }}</div>
        <div class="stat-label"><i class="fas fa-clock"></i> Pending</div>
    </div>
    <div class="stat-card">
        <div class="stat-num" style="-webkit-text-fill-color:#a855f7">{{ $stats['processing'] }}</div>
        <div class="stat-label"><i class="fas fa-cog"></i> Processing</div>
    </div>
    <div class="stat-card">
        <div class="stat-num" style="-webkit-text-fill-color:#22c55e">{{ $stats['completed'] }}</div>
        <div class="stat-label"><i class="fas fa-check-circle"></i> Completed</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">{{ $stats['total_products'] }}</div>
        <div class="stat-label"><i class="fas fa-box"></i> Produk</div>
    </div>
</div>

<div class="quick-links">
    <a href="/admin/orders" class="btn btn-primary"><i class="fas fa-list"></i> Kelola Order</a>
    <a href="/admin/products" class="btn btn-outline"><i class="fas fa-box"></i> Kelola Produk</a>
    <a href="/admin/products/create" class="btn btn-outline"><i class="fas fa-plus"></i> Tambah Produk</a>
</div>

<div class="table-wrap">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);font-weight:600">Order Terbaru</div>
    <table>
        <thead><tr>
            <th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Aksi</th>
        </tr></thead>
        <tbody>
        @forelse($orders as $order)
        <tr>
            <td><span style="font-weight:600">{{ $order->order_number }}</span></td>
            <td>{{ $order->customer->name ?? $order->customer_name }}</td>
            <td style="color:var(--accent);font-weight:600">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            <td><span class="badge status-{{ $order->status }}" style="border-radius:50px;padding:4px 12px">{{ ucfirst($order->status) }}</span></td>
            <td><a href="/admin/orders" style="color:var(--primary);font-size:0.85rem">Detail →</a></td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:30px">Belum ada order</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
