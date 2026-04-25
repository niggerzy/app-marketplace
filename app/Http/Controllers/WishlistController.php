<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with('product')
            ->paginate(12);

        return view('wishlists.index', compact('wishlists'));
    }

    public function add(Product $product)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['error' => 'Produk sudah ada di wishlist']);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return redirect()->back()
            ->with('success', 'Produk berhasil ditambahkan ke wishlist!');
    }

    public function remove(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        return redirect()->back()
            ->with('success', 'Produk berhasil dihapus dari wishlist!');
    }

    public function addToCart(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect('/cart/add')->with([
            'product_id' => $wishlist->product_id,
            'quantity' => 1,
        ]);
    }
}
