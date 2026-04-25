<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    protected const CART_KEY = 'shopping_cart';

    public function add(int $productId, int $quantity = 1): array
    {
        $product = Product::find($productId);

        if (!$product || !$product->is_active) {
            throw new \Exception('Produk tidak ditemukan atau tidak aktif');
        }

        if ($quantity > $product->stock) {
            throw new \Exception('Stok tidak cukup. Stok tersedia: ' . $product->stock);
        }

        $cart = session()->get(self::CART_KEY, []);

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                throw new \Exception('Stok tidak cukup untuk kuantitas yang diminta');
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->getDiscountedPriceAttribute(),
            ];
        }

        session()->put(self::CART_KEY, $cart);

        return $this->getCart();
    }

    public function remove(int $productId): array
    {
        $cart = session()->get(self::CART_KEY, []);
        unset($cart[$productId]);
        session()->put(self::CART_KEY, $cart);

        return $this->getCart();
    }

    public function update(int $productId, int $quantity): array
    {
        $cart = session()->get(self::CART_KEY, []);

        if (!isset($cart[$productId])) {
            throw new \Exception('Produk tidak ada di keranjang');
        }

        $product = Product::find($productId);

        if ($quantity > $product->stock) {
            throw new \Exception('Stok tidak cukup');
        }

        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        $cart[$productId]['quantity'] = $quantity;
        session()->put(self::CART_KEY, $cart);

        return $this->getCart();
    }

    public function getCart(): array
    {
        $cart = session()->get(self::CART_KEY, []);
        $items = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $items[] = [
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ];
            }
        }

        return [
            'items' => $items,
            'count' => collect($items)->sum('quantity'),
            'total' => collect($items)->sum('subtotal'),
            'formatted_total' => 'Rp ' . number_format(collect($items)->sum('subtotal'), 0, ',', '.'),
        ];
    }

    public function getItems(): Collection
    {
        return collect($this->getCart()['items']);
    }

    public function getTotal(): float
    {
        return $this->getCart()['total'];
    }

    public function getCount(): int
    {
        return $this->getCart()['count'];
    }

    public function clear(): void
    {
        session()->forget(self::CART_KEY);
    }

    public function isEmpty(): bool
    {
        return $this->getCount() === 0;
    }
}
