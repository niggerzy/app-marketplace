<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $this->cartService->add(
                $validated['product_id'],
                $validated['quantity']
            );

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $productId)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $this->cartService->update($productId, $validated['quantity']);

            return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function remove(int $productId)
    {
        $this->cartService->remove($productId);

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect('/cart')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}
