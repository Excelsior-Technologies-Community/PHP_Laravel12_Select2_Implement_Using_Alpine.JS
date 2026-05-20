<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->whereJsonContains('categories', $request->category);
        }

        $products = $query->latest()->paginate(5)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function getSuggestions(Request $request)
    {
        $search = $request->input('search');

        if (empty($search)) {
            return response()->json([]);
        }

        $suggestions = Product::where('name', 'like', '%' . $search . '%')
            ->select('id', 'name')
            ->limit(5)
            ->get();

        return response()->json($suggestions);
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'categories' => 'required|array',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'categories' => $request->categories,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'categories' => 'required|array',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'categories' => $request->categories,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->latest()->get();
        return view('products.trash', compact('products'));
    }

    public function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('products.trash')
            ->with('success', 'Product restored successfully');
    }

    public function forceDelete($id)
    {
        Product::withTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('products.trash')
            ->with('success', 'Product permanently deleted');
    }
}