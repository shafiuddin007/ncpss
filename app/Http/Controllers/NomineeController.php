<?php

namespace App\Http\Controllers;

use App\Models\Nominee;
use Illuminate\Http\Request;

class NomineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nominees = Nominee::all();
        return view('nominees.index', compact('nominees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('nominees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'nid_birth_no' => 'required|string|max:255',
            'nominee_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'contact_no' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        Nominee::create($validated);

        return redirect()->route('nominees.index')->with('success', 'Nominee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nominee $nominee)
    {
        return view('nominees.show', compact('nominee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nominee $nominee)
    {
        return view('nominees.edit', compact('nominee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nominee $nominee)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'nid_birth_no' => 'required|string|max:255',
            'nominee_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'contact_no' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $nominee->update($validated);

        return redirect()->route('nominees.index')->with('success', 'Nominee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nominee $nominee)
    {
        $nominee->delete();

        return redirect()->route('nominees.index')->with('success', 'Nominee deleted successfully.');
    }
}