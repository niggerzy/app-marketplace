<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10|max:1000',
        ]);

        $order = auth()->user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->latest()
            ->first();

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'order_id' => $order?->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_verified' => (bool) $order,
        ]);

        $this->updateProductRating($product);

        return redirect()->back()
            ->with('success', 'Review berhasil ditambahkan!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $product = $review->product;
        $review->delete();

        $this->updateProductRating($product);

        return redirect()->back()
            ->with('success', 'Review berhasil dihapus!');
    }

    private function updateProductRating(Product $product): void
    {
        $avgRating = $product->reviews()
            ->where('is_active', true)
            ->avg('rating');

        $reviewCount = $product->reviews()
            ->where('is_active', true)
            ->count();

        $product->update([
            'rating' => round($avgRating ?? 0, 2),
            'reviews_count' => $reviewCount,
        ]);
    }
}
