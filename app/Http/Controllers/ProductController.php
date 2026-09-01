<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $query = $shop->products()->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = $shop->categories()->withCount('products')->get();

        return view('dashboard.products.index', compact('products', 'categories'));
    }

    public function create(Request $request)
    {
        $shop = $request->user()->shop;
        $categories = $shop->categories()->get();

        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'new_category_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        if (empty($validated['category_id']) && !empty($validated['new_category_name'])) {
            $category = Category::create([
                'name' => $validated['new_category_name'],
                'shop_id' => $shop->id,
                'slug' => Str::slug($validated['new_category_name']),
            ]);
            $validated['category_id'] = $category->id;
        }

        unset($validated['new_category_name']);

        $validated['shop_id'] = $shop->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Product::create($validated);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح.');
    }

    public function edit(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $product = $shop->products()->findOrFail($id);
        $categories = $shop->categories()->get();

        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $product = $shop->products()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'new_category_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        if (empty($validated['category_id']) && !empty($validated['new_category_name'])) {
            $category = Category::create([
                'name' => $validated['new_category_name'],
                'shop_id' => $shop->id,
                'slug' => Str::slug($validated['new_category_name']),
            ]);
            $validated['category_id'] = $category->id;
        }

        unset($validated['new_category_name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $product = $shop->products()->findOrFail($id);
        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('success', 'تم حذف المنتج بنجاح.');
    }
}
