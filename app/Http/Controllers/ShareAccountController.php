<?php

namespace App\Http\Controllers;

use App\Models\ShareAccount;
use Inertia\Inertia;

class ShareAccountController extends Controller
{
    public function index()
    {
        $shareAccounts = ShareAccount::all();
        return Inertia::render('ShareAccounts/Index', [
            'shareAccounts' => $shareAccounts
        ]);
    }

    public function create()
    {
        // You may want to pass members and nominees for dropdowns
        return Inertia::render('ShareAccounts/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'share_account_number' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'employer_name' => 'nullable|string|max:255',
            'employer_address' => 'nullable|string|max:255',
            'employer_email' => 'nullable|email|max:255',
            'employer_phone' => 'nullable|string|max:255',
            'nominee_id' => 'nullable|exists:nominees,id',
        ]);

        \App\Models\ShareAccount::create($validated);

        return redirect()->route('share-accounts.index')->with('success', 'Share account created successfully.');
    }
}
