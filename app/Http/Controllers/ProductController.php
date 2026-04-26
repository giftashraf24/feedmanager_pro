<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $products = $query->orderBy('name')->get();
        $types    = Product::select('type')->distinct()->orderBy('type')->pluck('type');

        return view('products.index', compact('products', 'types'));
    }

    public function create()
    {
        $types = Product::select('type')->distinct()->orderBy('type')->pluck('type');
        return view('products.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'type'           => ['required', 'string', 'max:100'],
            'price'          => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', "Product \"{$validated['name']}\" created successfully.");
    }

    public function edit(Product $product)
    {
        $types = Product::select('type')->distinct()->orderBy('type')->pluck('type');
        return view('products.edit', compact('product', 'types'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'type'           => ['required', 'string', 'max:100'],
            'price'          => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', "Product \"{$product->name}\" updated successfully.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Product \"{$name}\" deleted.");
    }
}
