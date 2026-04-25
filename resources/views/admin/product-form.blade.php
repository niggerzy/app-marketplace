@extends('layouts.app')
@section('title', isset($product) ? 'Edit Produk' : 'Tambah Produk')

@section('styles')
<style>
    .form-wrap {
        max-width: 560px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 7px;
        color: var(--text-muted);
    }

    .error-box {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid var(--danger);
        color: #ef4444;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        margin-bottom: 16px;
        font-size: 0.9rem;
    }

    .success-box {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid #22c55e;
        color: #22c55e;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        margin-bottom: 16px;
        font-size: 0.9rem;
    }

    .input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 768px) {
        .input-group {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="form-wrap fade-up">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
        <a href="/admin/products" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 700;">
            {{ isset($product) ? '✏️ Edit Produk' : '➕ Tambah Produk' }}
        </h1>
    </div>

    <div class="card">
        @if ($errors->any())
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-box">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST"
            action="{{ isset($product) ? '/admin/products/' . $product->id : '/admin/products' }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <div class="input-group">
                <div class="form-group">
                    <label class="form-label" for="name">Nama Produk <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="name" name="name" value="{{ $product->name ?? old('name') }}"
                        class="input-modern @error('name') is-invalid @enderror" placeholder="Masukkan nama produk"
                        required>
                    @error('name')
                        <small style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="sku">SKU <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="sku" name="sku" value="{{ $product->sku ?? old('sku') }}"
                        class="input-modern @error('sku') is-invalid @enderror" placeholder="PROD-001" required>
                    @error('sku')
                        <small style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="input-group">
                <div class="form-group">
                    <label class="form-label" for="price">Harga (Rp) <span style="color: #ef4444;">*</span></label>
                    <input type="number" id="price" name="price" value="{{ $product->price ?? old('price') }}"
                        class="input-modern @error('price') is-invalid @enderror" placeholder="50000" min="0"
                        required>
                    @error('price')
                        <small style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="stock">Stok <span style="color: #ef4444;">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ $product->stock ?? old('stock') }}"
                        class="input-modern @error('stock') is-invalid @enderror" placeholder="100" min="0" required>
                    @error('stock')
                        <small style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="category_id">Kategori <span style="color: #ef4444;">*</span></label>
                <select id="category_id" name="category_id"
                    class="input-modern @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories ?? [] as $category)
                        <option value="{{ $category->id }}"
                            {{ (old('category_id') ?? $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi <span style="color: #ef4444;">*</span></label>
                <textarea id="description" name="description" rows="5"
                    class="input-modern @error('description') is-invalid @enderror"
                    style="border-radius: var(--radius-md); resize: vertical;" placeholder="Deskripsi produk..."
                    required>{{ $product->description ?? old('description') }}</textarea>
                @error('description')
                    <small style="color: #ef4444; font-size: 0.85rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i>
                    {{ isset($product) ? 'Update Produk' : 'Simpan Produk' }}
                </button>
                <a href="/admin/products" class="btn btn-outline" style="flex: 1; text-align: center;">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
