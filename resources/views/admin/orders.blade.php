@extends('layouts.app')
@section('title', 'Kelola Order')
@section('styles')
<style>
.filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.filter-tab { padding: 6px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; border: 1px solid var(--border); color: var(--text-muted); transition: all 0.2s; }
.filter-tab.active, .filter-tab:hover { background: linear-gradient(135deg, var(--primary), var(--accent)); color: white; border-color: transparent; }
.order-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 20px; margin-bottom: 14px; }
.order-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 0.88rem; color: var(--text-muted); margin-bottom: 14px; }
.order-meta span { display: flex; gap: 6px; align-items: center; }
.update-form { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; padding-top: 14px; border-top: 1px solid var(--border); }
</style>
@endsection
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px">
    <div>
        <h1 style="font-size:1.8rem;font-weight:700">📋 Kelola Order</h1>
        <p style="color:var(--text-muted);margin-top:4px">Update status pesanan pelanggan</p>
    </div>
    <a href="/admin" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>

@if(session('success'))
<div style="background:rgba(34,197,94,0.1);border:1px solid var(--success);color:#22c55e;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:16px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<div class="filter-tabs">
    @foreach(['' => 'Semua', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
        <a href="/admin/orders{{ $val ? '?status='.$val : '' }}"
           class="filter-tab {{ request('status', '') === $val ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</div>

@forelse($orders as $order)
<div class="order-card">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;flex-wrap:wrap;gap:8px">
        <div>
            <span style="font-weight:700;font-size:1rem">{{ $order->order_number }}</span>
            <span style="color:var(--text-muted);font-size:0.82rem;margin-left:10px">{{ $order->created_at->format('d M Y H:i') }}</span>
        </div>
        <span class="status-{{ $order->status }}" style="padding:5px 14px;border-radius:50px;font-size:0.8rem;font-weight:700">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="order-meta">
        <span><i class="fas fa-user" style="color:var(--primary)"></i> {{ $order->customer_name }}</span>
        <span><i class="fas fa-phone" style="color:var(--accent)"></i> {{ $order->customer_phone }}</span>
        <span><i class="fas fa-map-marker-alt" style="color:var(--danger)"></i> {{ Str::limit($order->customer_address, 40) }}</span>
        <span style="color:var(--accent);font-weight:700"><i class="fas fa-tag"></i> Rp {{ number_format($order->total, 0, ',', '.') }}</span>
    </div>

    <form method="POST" action="/admin/orders/{{ $order->id }}/status" class="update-form">
        @csrf
        <select name="status" class="select-modern">
            @foreach(['pending','confirmed','processing','shipped','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <input type="text" name="admin_notes" value="{{ $order->admin_notes }}" placeholder="Catatan untuk customer..." class="input-modern" style="flex:1;border-radius:50px;padding:8px 16px">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Update</button>
    </form>
</div>
@empty
<div style="text-align:center;padding:60px;color:var(--text-muted)">
    <i class="fas fa-inbox" style="font-size:3rem;opacity:0.3;display:block;margin-bottom:16px"></i>
    <p>Tidak ada order dengan status ini</p>
</div>
@endforelse
<div style="margin-top:16px">{{ $orders->links() }}</div>
@endsection
