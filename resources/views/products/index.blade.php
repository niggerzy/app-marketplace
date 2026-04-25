@extends('layouts.app')
@section('title', 'Produk')
@section('styles')
<style>
.product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; margin-top: 20px; }
.product-card { background: var(--bg-card); border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border); transition: transform 0.3s, border-color 0.3s; display: flex; flex-direction: column; }
.product-card:hover { transform: translateY(-8px); box-shadow: var(--shadow); border-color: var(--primary); }
.card-img-wrap { height: 180px; background: linear-gradient(135deg, #1e293b, #0f172a); display: flex; align-items: center; justify-content: center; }
.card-img-wrap i { font-size: 3rem; color: var(--border); }
.card-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
.card-body { padding: 16px; flex-grow: 1; display: flex; flex-direction: column; }
.card-title { font-weight: 600; margin-bottom: 6px; font-size: 1rem; }
.card-desc { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 10px; flex-grow: 1; }
.card-price { color: var(--accent); font-weight: 700; font-size: 1.15rem; margin-bottom: 6px; }
.card-stock { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 14px; }
.card-actions { display: flex; gap: 8px; }
.page-header { margin-bottom: 10px; }
.page-header h1 { font-size: 1.8rem; font-weight: 700; }
.page-header p { color: var(--text-muted); margin-top: 4px; }
.empty-state { text-align: center; padding: 60px 0; color: var(--text-muted); grid-column: 1/-1; }
.empty-state i { font-size: 3rem; margin-bottom: 15px; opacity: 0.4; }
</style>
@endsection
@section('content')
<div class="page-header fade-up">
    <h1>🛍️ Semua Produk</h1>
    <p>Temukan produk terbaik dengan harga terjangkau</p>
</div>

<div class="product-grid">
@forelse($products as $product)
    <div class="product-card">
        <div class="card-img-wrap">
            @if($product->image_path)
                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}">
            @else
                <i class="fas fa-box-open"></i>
            @endif
        </div>
        <div class="card-body">
            <div class="card-title">{{ $product->name }}</div>
            <div class="card-desc">{{ Str::limit($product->description, 80) }}</div>
            <div class="card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            <div class="card-stock"><i class="fas fa-cubes"></i> Stok: {{ $product->stock }}</div>
            <div class="card-actions">
                <a href="/products/{{ $product->id }}" class="btn btn-outline btn-sm" style="flex:1">
                    <i class="fas fa-eye"></i> Detail
                </a>
                @auth
                    <button onclick="addToCart({{ $product->id }})" class="btn btn-primary btn-sm" style="flex:1">
                        <i class="fas fa-cart-plus"></i> Cart
                    </button>
                @else
                    <a href="/login" class="btn btn-primary btn-sm" style="flex:1">
                        <i class="fas fa-cart-plus"></i> Cart
                    </a>
                @endauth
            </div>
        </div>
    </div>
@empty
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>Belum ada produk tersedia.</p>
    </div>
@endforelse
</div>

<div style="margin-top:24px">{{ $products->links() }}</div>
@endsection
@section('scripts')
<script>
function addToCart(id) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let item = cart.find(i => i.id === id);
    if (item) item.quantity += 1;
    else cart.push({ id, quantity: 1 });
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartBadge();
    showToast('Produk ditambahkan ke keranjang!', 'success');
}
</script>
@endsection
