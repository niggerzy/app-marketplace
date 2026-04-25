<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function create(int $userId, array $data): Order
    {
        return DB::transaction(function () use ($userId, $data) {
            $cart = $this->cartService->getCart();

            if (empty($cart['items'])) {
                throw new \Exception('Keranjang belanja kosong');
            }

            $subtotal = $cart['total'];
            $tax = round($subtotal * 0.1, 2);
            $shippingCost = $data['shipping_cost'] ?? 25000;
            $discount = $data['discount'] ?? 0;
            $total = $subtotal + $tax + $shippingCost - $discount;

            $order = Order::create([
                'user_id' => $userId,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'shipping_address' => $data['shipping_address'],
                'shipping_city' => $data['shipping_city'],
                'shipping_state' => $data['shipping_state'],
                'shipping_postal_code' => $data['shipping_postal_code'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product']['name'],
                    'product_sku' => $item['product']['sku'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                Product::find($item['product_id'])->decrement('stock', $item['quantity']);
            }

            $this->cartService->clear();

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);

        if ($status === 'paid') {
            $order->update(['paid_at' => now()]);
        } elseif ($status === 'shipped') {
            $order->update(['shipped_at' => now()]);
        } elseif ($status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return $order;
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus): Order
    {
        $order->update(['payment_status' => $paymentStatus]);

        if ($paymentStatus === 'completed') {
            $this->updateStatus($order, 'paid');
        }

        return $order;
    }

    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            if ($order->status !== 'pending') {
                throw new \Exception('Pesanan tidak bisa dibatalkan');
            }

            foreach ($order->items as $item) {
                Product::find($item->product_id)->increment('stock', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            return $order;
        });
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = Order::whereDate('created_at', now()->toDateString())->count() + 1;

        return 'ORD-' . $date . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
