<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();

        return Inertia::render('product/index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return Inertia::render('product/create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:savings,loan,credit_card,investment',
            'description' => 'nullable|string',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'min_balance' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',
            'loan_term_months' => 'nullable|integer|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'required|boolean',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return Inertia::render('product/edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|in:savings,loan,credit_card,investment',
            'description' => 'nullable|string',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'min_balance' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',
            'loan_term_months' => 'nullable|integer|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'required|boolean',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
