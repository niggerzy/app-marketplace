<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') abort(403, 'Akses ditolak.');
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $stats = [
            'total_orders' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'total_products' => Product::count(),
        ];
        $orders = Order::with('customer')->latest()->take(10)->get();
        return view('admin.dashboard', compact('stats', 'orders'));
    }

    public function orders(Request $request)
    {
        $this->checkAdmin();
        $query = Order::with('customer')->latest();
        if ($request->status) $query->where('status', $request->status);
        $orders = $query->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->checkAdmin();
        $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled']);
        $order->update(['status' => $request->status, 'admin_notes' => $request->admin_notes]);
        return back()->with('success', 'Status order diupdate.');
    }

    public function products()
    {
        $this->checkAdmin();
        $products = Product::latest()->paginate(15);
        return view('admin.products', compact('products'));
    }

    public function createProduct()
    {
        $this->checkAdmin();
        return view('admin.product-form');
    }

    public function storeProduct(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required', 'price' => 'required|numeric',
            'stock' => 'required|integer', 'description' => 'nullable',
        ]);
        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);
        return redirect('/admin/products')->with('success', 'Produk ditambahkan.');
    }

    public function editProduct(Product $product)
    {
        $this->checkAdmin();
        return view('admin.product-form', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->checkAdmin();
        $request->validate([
            'name' => 'required', 'price' => 'required|numeric',
            'stock' => 'required|integer', 'description' => 'nullable',
        ]);
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $product->id,
            'created_by' => $product->created_by,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'created_by' => auth()->id(),
            'created_by' => auth()->id(),
        ]);
        return redirect('/admin/products')->with('success', 'Produk diupdate.');
    }

    public function deleteProduct(Product $product)
    {
        $this->checkAdmin();
        $product->delete();
        return back()->with('success', 'Produk dihapus.');
    }
}
