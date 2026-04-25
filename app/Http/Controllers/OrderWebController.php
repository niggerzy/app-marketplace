<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Exceptions\InsufficientStockException;
use Illuminate\Http\Request;

class OrderWebController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function checkout()
    {
        return view('orders.checkout');
    }

    public function myOrders()
    {
        $orders = Order::with('items')
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('orders.my-orders', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required',
            'customer_phone'   => 'required',
            'customer_address' => 'required',
            'items'            => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        try {
            $order = $this->orderService->createOrder(
                auth()->user(),
                $request->items,
                [
                    'name'    => $request->customer_name,
                    'phone'   => $request->customer_phone,
                    'address' => $request->customer_address,
                ]
            );
            return response()->json([
                'success'  => true,
                'message'  => 'Order berhasil dibuat.',
                'order_id' => $order->id,
            ]);
        } catch (InsufficientStockException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function tracking($id)
    {
        $order = Order::with('items')->where('customer_id', auth()->id())->findOrFail($id);
        return view('orders.tracking', compact('order'));
    }
}
