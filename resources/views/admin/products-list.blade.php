@extends('layouts.app')
@section('title', 'Daftar Produk')

@section('styles')
<style>
    .table-wrap {
        overflow-x: auto;
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .table-modern thead {
        background: var(--bg-secondary);
        border-bottom: 2px solid var(--border-color);
    }

    .table-modern th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
    }

    .table-modern td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
    }

    .table-modern tbody tr:hover {
        background: var(--bg-secondary);
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }

    .badge-warning {
        background: rgba(251, 146, 60, 0.2);
        color: #fb923c;
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .search-box {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
    }

    .search-box input {
        flex: 1;
        padding: 10px 14px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
    }
</style>
@endsection

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <h1 style="font-size: 1.8rem; font-weight: 700;">📦 Daftar Produk</h1>
        <a href="/admin/products/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    <div class="card">
        @if (session('success'))
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #22c55e; padding: 12px 16px; border-radius: var(--radius-md); margin-bottom: 16px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari produk..." class="input-modern"
                style="padding: 10px 14px; flex: 1;">
            <button type="button" class="btn btn-outline" onclick="filterTable()">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>

        <div class="table-wrap">
            <table class="table-modern" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small style="color: var(--text-muted);">{{ Str::limit($product->description, 40) }}</small>
                            </td>
                            <td><code style="background: var(--bg-secondary); padding: 2px 6px; border-radius: 3px;">{{ $product->sku }}</code>
                            </td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $product->stock > 20 ? 'badge-success' : ($product->stock > 0 ? 'badge-warning' : 'badge-danger') }}">
                                    {{ $product->stock }} unit
                                </span>
                            </td>
                            <td>
                                @if ($product->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/admin/products/{{ $product->id }}/edit" class="btn btn-sm btn-outline"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 32px;">
                                <i class="fas fa-inbox" style="font-size: 2rem; color: var(--text-muted); margin-bottom: 12px;"></i>
                                <p style="color: var(--text-muted); margin-top: 12px;">Belum ada produk. <a href="/admin/products/create">Buat yang baru</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Hidden form for delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
    function filterTable() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const table = document.getElementById('productsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

        Array.from(rows).forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(input) ? '' : 'none';
        });
    }

    function confirmDelete(id, name) {
        if (confirm(`Yakin ingin menghapus produk "${name}"?`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/products/${id}`;
            form.submit();
        }
    }

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
</script>
@endsection
