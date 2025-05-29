<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Js;
use Inertia\Inertia;
use Illuminate\Http\JsonResponse;
use App\Enums\ProductType;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::all();

        // Prepare product type options from enum
        $productTypeOptions = collect(ProductType::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => ucfirst(str_replace('_', ' ', $case->name)),
        ])->all();

        return Inertia::render('product/index', [
            'products' => $products,
            'productTypeOptions' => $productTypeOptions,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $productTypes = collect(ProductType::cases())
            ->map(fn($case) => [
                'value' => $case->value,
                'label' => ucwords(str_replace('_', ' ', $case->name)),
            ])
            ->values();

        return Inertia::render('product/create', [
            'productTypes' => $productTypes,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required',
            'description' => 'nullable|string',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'min_balance' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',
            'loan_term_months' => 'nullable|integer|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'required|boolean',
        ]);

        $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $productTypes = collect(ProductType::cases())
            ->map(fn($case) => [
                'value' => $case->value,
                'label' => ucwords(str_replace('_', ' ', $case->name)),
            ])
            ->values();

        return Inertia::render('product/edit', [
            'product' => $product,
            'productTypes' => $productTypes,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required',
            'description' => 'nullable|string',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'min_balance' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',
            'loan_term_months' => 'nullable|integer|min:0',
            'currency' => 'required|string|size:3',
            'is_active' => 'required|boolean',
        ]);
        $validated['is_active'] = filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request, $productId): JsonResponse
    {
        try {
            $product = Product::findOrFail($productId);
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete the product.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
