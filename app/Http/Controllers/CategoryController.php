<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->update(['image' => $imagePath]);
        }

        return redirect('/admin/categories')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->update(['image' => $imagePath]);
        }

        return redirect('/admin/categories')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect('/admin/categories')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
