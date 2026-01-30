<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display all products
     */
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = ['Electronics', 'Fashion', 'Books', 'Furniture', 'Sports'];

        return view('products.create', compact('categories'));
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'categories'  => 'required|array',
        ]);

        Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'categories'  => $request->categories,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product saved successfully');
    }

    /**
     * Show single product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ['Electronics', 'Fashion', 'Books', 'Furniture', 'Sports'];

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'categories'  => 'required|array',
        ]);

        $product->update([
            'name'        => $request->name,
            'description' => $request->description,
            'categories'  => $request->categories,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Soft delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}