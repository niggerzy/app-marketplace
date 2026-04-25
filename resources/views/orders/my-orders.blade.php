@extends('layouts.app')
@section('title', 'Pesanan Saya')
@section('styles')
<style>
.order-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 20px; margin-bottom: 16px; transition: border-color 0.2s; }
.order-card:hover { border-color: var(--primary); }
.order-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; flex-wrap: wrap; gap: 8px; }
.order-num { font-weight: 700; font-size: 1rem; }
.order-date { color: var(--text-muted); font-size: 0.85rem; margin-top: 3px; }
.status-badge { padding: 5px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; }
.order-items { border-top: 1px solid var(--border); padding-top: 12px; margin-bottom: 14px; }
.order-item-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 0.9rem; color: var(--text-muted); }
.order-footer { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
.order-total { font-weight: 700; font-size: 1.05rem; }
.progress-wrap { margin-top: 14px; }
.progress-labels { display: flex; justify-content: space-between; font-size: 0.72rem; margin-bottom: 6px; }
.progress-bar { width: 100%; background: var(--border); border-radius: 50px; height: 6px; }
.progress-fill { height: 6px; border-radius: 50px; background: linear-gradient(to right, var(--primary), var(--accent)); transition: width 0.5s; }
</style>
@endsection
@section('content')
<div class="fade-up" style="margin-bottom:24px">
    <h1 style="font-size:1.8rem;font-weight:700">📦 Pesanan Saya</h1>
    <p style="color:var(--text-muted);margin-top:4px">Pantau status pesanan kamu di sini</p>
</div>

@forelse($orders as $order)
@php
    $steps = ['pending','confirmed','processing','shipped','completed'];
    $step = array_search($order->status, $steps);
    $pct = $step === false ? 0 : ($step / (count($steps)-1)) * 100;
    $statusClass = 'status-' . $order->status;
@endphp
<div class="order-card">
    <div class="order-header">
        <div>
            <div class="order-num">{{ $order->order_number }}</div>
            <div class="order-date"><i class="fas fa-clock"></i> {{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        <span class="status-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="order-items">
        @foreach($order->items as $item)
        <div class="order-item-row">
            <span>{{ $item->product_name ?? 'Produk #'.$item->product_id }} × {{ $item->quantity }}</span>
            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>

    <div class="order-footer">
        <div class="order-total" style="color:var(--accent)">
            Total: Rp {{ number_format($order->total, 0, ',', '.') }}
        </div>
        <span style="font-size:0.85rem;color:var(--text-muted)">
            <i class="fas fa-money-bill-wave"></i> Bayar COD
        </span>
    </div>

    @if($order->admin_notes)
    <div style="margin-top:12px;padding:10px 14px;background:var(--glass);border-radius:var(--radius-md);font-size:0.88rem;color:var(--text-muted)">
        <i class="fas fa-comment-dots" style="color:var(--primary)"></i> {{ $order->admin_notes }}
    </div>
    @endif

    @if($order->status !== 'cancelled')
    <div class="progress-wrap">
        <div class="progress-labels">
            @foreach($steps as $i => $s)
                <span style="{{ $step >= $i ? 'color:var(--primary);font-weight:600' : '' }}">{{ ucfirst($s) }}</span>
            @endforeach
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width:{{ $pct }}%"></div>
        </div>
    </div>
    @else
    <div style="margin-top:10px;color:var(--danger);font-size:0.88rem;font-weight:600">
        <i class="fas fa-times-circle"></i> Order dibatalkan
    </div>
    @endif
</div>
@empty
<div style="text-align:center;padding:60px 0;color:var(--text-muted)">
    <i class="fas fa-box-open" style="font-size:3rem;opacity:0.3;display:block;margin-bottom:16px"></i>
    <p style="font-size:1.1rem;margin-bottom:16px">Belum ada pesanan</p>
    <a href="/products" class="btn btn-primary"><i class="fas fa-store"></i> Mulai Belanja</a>
</div>
@endforelse

<div style="margin-top:20px">{{ $orders->links() }}</div>
@endsection
