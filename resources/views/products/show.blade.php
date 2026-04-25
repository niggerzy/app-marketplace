@extends('layouts.app')
@section('title', $product->name)
@section('content')
<div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6">
    @if($product->image_path)
        <img src="{{ Storage::url($product->image_path) }}" class="w-full h-64 object-cover rounded mb-4">
    @endif
    <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
    <p class="text-gray-600 mb-4">{{ $product->description }}</p>
    <div class="flex justify-between items-center mb-4">
        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
        <span class="text-sm bg-gray-200 px-2 py-1 rounded">Stok: {{ $product->stock }}</span>
    </div>
    @auth
        <button onclick="addToCart({{ $product->id }}); window.location='/orders/checkout';"
            class="w-full bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700">
            Beli Sekarang
        </button>
    @else
        <a href="/login" class="block w-full text-center bg-green-600 text-white py-2 rounded font-semibold hover:bg-green-700">
            Login untuk Beli
        </a>
    @endauth
    <a href="/products" class="block mt-3 text-center text-sm text-blue-600">← Kembali</a>
</div>
<script>
function addToCart(id) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let item = cart.find(i => i.id === id);
    if (item) item.quantity += 1;
    else cart.push({ id: id, quantity: 1 });
    localStorage.setItem('cart', JSON.stringify(cart));
}
</script>
@endsection
