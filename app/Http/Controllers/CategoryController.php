<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $shop = $request->user()->shop;

        $categories = $shop->categories()
            ->withCount('products')
            ->latest()
            ->get();

        return view('dashboard.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $shop = $request->user()->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ]);

        $validated['shop_id'] = $shop->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        $validated['is_active'] = $request->boolean('is_active');

        $shop->categories()->create($validated);

        return back()->with('success', 'تم إضافة التصنيف بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $category = $shop->categories()->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return back()->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroy(Request $request, $id)
    {
        $shop = $request->user()->shop;

        $category = $shop->categories()->findOrFail($id);
        $category->delete();

        return back()->with('success', 'تم حذف التصنيف بنجاح.');
    }
}
