@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<h1 class="text-2xl font-bold mb-6">Checkout</h1>

<div id="cart-empty" class="hidden text-center py-10 text-gray-400">
    <p class="text-lg">Keranjang kosong.</p>
    <a href="/products" class="text-blue-600 mt-2 inline-block">← Belanja dulu</a>
</div>

<div id="cart-content" class="hidden">
    <!-- Cart Items -->
    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <h2 class="font-bold mb-3">Produk yang dipesan</h2>
        <div id="cart-items"></div>
        <div class="border-t mt-3 pt-3 flex justify-between font-bold">
            <span>Total</span>
            <span id="cart-total">Rp 0</span>
        </div>
    </div>

    <!-- Form Data Pengiriman -->
    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <h2 class="font-bold mb-3">Data Pengiriman</h2>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Nama Penerima</label>
            <input type="text" id="customer_name" class="w-full border rounded px-3 py-2" placeholder="Nama lengkap" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">No. HP</label>
            <input type="text" id="customer_phone" class="w-full border rounded px-3 py-2" placeholder="08xxxxxxxxxx" required>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Alamat Lengkap</label>
            <textarea id="customer_address" rows="3" class="w-full border rounded px-3 py-2" placeholder="Jalan, Kota, Kode Pos" required></textarea>
        </div>
    </div>

    <div id="error-msg" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4"></div>
    <div id="success-msg" class="hidden bg-green-100 text-green-700 p-3 rounded mb-4"></div>

    <button onclick="placeOrder()"
        class="w-full bg-blue-600 text-white py-3 rounded font-bold hover:bg-blue-700 text-lg">
        🛒 Place Order (COD)
    </button>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
let productData = {};

async function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    if (cart.length === 0) {
        document.getElementById('cart-empty').classList.remove('hidden');
        return;
    }
    document.getElementById('cart-content').classList.remove('hidden');

    // Fetch product details for each item
    const ids = cart.map(i => i.id);
    try {
        const res = await fetch('/api/products-by-ids?ids=' + ids.join(','));
        const data = await res.json();
        productData = {};
        data.forEach(p => productData[p.id] = p);
    } catch(e) {
        // fallback: show without price
    }

    renderCart(cart);
}

function renderCart(cart) {
    let html = '';
    let total = 0;

    cart.forEach((item, index) => {
        const product = productData[item.id];
        const name = product ? product.name : 'Produk #' + item.id;
        const price = product ? product.price : 0;
        const subtotal = price * item.quantity;
        total += subtotal;

        html += `
        <div class="flex justify-between items-center py-2 border-b">
            <div class="flex-1">
                <div class="font-medium">${name}</div>
                <div class="text-sm text-gray-500">Rp ${formatNum(price)} × ${item.quantity}</div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="changeQty(${index}, -1)" class="w-7 h-7 border rounded text-lg leading-none">−</button>
                <span>${item.quantity}</span>
                <button onclick="changeQty(${index}, 1)" class="w-7 h-7 border rounded text-lg leading-none">+</button>
                <button onclick="removeItem(${index})" class="text-red-500 ml-2 text-sm">Hapus</button>
            </div>
            <div class="ml-4 font-semibold">Rp ${formatNum(subtotal)}</div>
        </div>`;
    });

    document.getElementById('cart-items').innerHTML = html;
    document.getElementById('cart-total').textContent = 'Rp ' + formatNum(total);
}

function changeQty(index, delta) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    if (cart.length === 0) location.reload();
    else renderCart(cart);
}

function removeItem(index) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    if (cart.length === 0) location.reload();
    else renderCart(cart);
}

function formatNum(n) {
    return Number(n).toLocaleString('id-ID');
}

async function placeOrder() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const name = document.getElementById('customer_name').value.trim();
    const phone = document.getElementById('customer_phone').value.trim();
    const address = document.getElementById('customer_address').value.trim();
    const errEl = document.getElementById('error-msg');
    const sucEl = document.getElementById('success-msg');

    errEl.classList.add('hidden');
    sucEl.classList.add('hidden');

    if (!name || !phone || !address) {
        errEl.textContent = 'Lengkapi semua data pengiriman.';
        errEl.classList.remove('hidden');
        return;
    }
    if (cart.length === 0) {
        errEl.textContent = 'Keranjang kosong.';
        errEl.classList.remove('hidden');
        return;
    }

    const items = cart.map(i => ({ product_id: i.id, quantity: i.quantity }));

    try {
        const res = await fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                customer_name: name,
                customer_phone: phone,
                customer_address: address,
                items: items,
            })
        });

        const data = await res.json();
        if (data.success || res.ok) {
            localStorage.removeItem('cart');
            sucEl.textContent = 'Order berhasil! ID Order: #' + (data.order_id || data.data?.id || '');
            sucEl.classList.remove('hidden');
            document.getElementById('cart-items').innerHTML = '';
            document.getElementById('cart-total').textContent = 'Rp 0';
            setTimeout(() => { window.location = '/products'; }, 2000);
        } else {
            errEl.textContent = data.message || 'Gagal membuat order.';
            errEl.classList.remove('hidden');
        }
    } catch(e) {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
        errEl.classList.remove('hidden');
    }
}

loadCart();
</script>
@endsection
