<?php

namespace App\Http\Controllers;

use App\Models\Introducer;
use Illuminate\Http\Request;

class IntroducerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $introducers = Introducer::with('member')->get();
        return view('introducers.index', compact('introducers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('introducers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|exists:members,id',
            'signature' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('signature')) {
            $validated['signature'] = $request->file('signature')->store('introducer_signatures', 'public');
        }

        $validated['date'] = now();

        Introducer::create($validated);

        return redirect()->route('introducers.index')->with('success', 'Introducer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Introducer $introducer)
    {
        return view('introducers.show', compact('introducer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Introducer $introducer)
    {
        return view('introducers.edit', compact('introducer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Introducer $introducer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|exists:members,id',
            'signature' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('signature')) {
            $validated['signature'] = $request->file('signature')->store('introducer_signatures', 'public');
        }

        $introducer->update($validated);

        return redirect()->route('introducers.index')->with('success', 'Introducer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Introducer $introducer)
    {
        $introducer->delete();

        return redirect()->route('introducers.index')->with('success', 'Introducer deleted successfully.');
    }
}