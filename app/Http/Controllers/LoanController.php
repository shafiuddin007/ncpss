<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanController extends Controller
{
    
    public function sellProduct($memberId)
    {
        $member = Member::findOrFail($memberId);
        $products = Product::where('is_active', true)
            ->get(['id', 'name', 'min_balance', 'max_loan_amount', 'interest_rate', 'loan_term_months']);
        return Inertia::render('sell-product/sell-product', [
            'member' => $member,
            'products' => $products,
        ]);
    }

    public function storeSoldProduct(Request $request, $memberId)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'min_balance' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',
            'loan_term_months' => 'nullable|integer|min:0',
        ]);

        $loan = Loan::create([
            'member_id' => $memberId,
            'product_id' => $validated['product_id'],
            'interest_rate' => $validated['interest_rate'],
            'min_balance' => $validated['min_balance'],
            'max_loan_amount' => $validated['max_loan_amount'],
            'loan_term_months' => $validated['loan_term_months'],
            'loan_number' => uniqid('LN-'),
            'status' => 'active',
        ]);

        return redirect()->route('member.list')->with('success', 'Product sold to member successfully.');
    }
}