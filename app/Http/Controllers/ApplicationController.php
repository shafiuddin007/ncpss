<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Enums\ApprovalStatus;
use App\Enums\ProductType;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::query();

        // Filter by search (application number)
        if ($request->filled('search')) {
            $query->where('application_number', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by product type (via polymorphic relation)
        if ($request->filled('product_type')) {
            if ($request->product_type === 'loan') {
                $query->where('model_type', \App\Models\Loan::class);
            } else {
                $query->whereHasMorph(
                    'model',
                    [\App\Models\Loan::class, \App\Models\Product::class],
                    function ($q) use ($request) {
                        $q->whereHas('product', function ($sub) use ($request) {
                            $sub->where('type', $request->product_type);
                        });
                    }
                );
            }
        }

        // dd($query->toSql(), $query->getBindings());

        $applications = $query->latest()->paginate(20)->withQueryString();

        // Get status options from enum
        $statusOptions = collect(ApprovalStatus::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ])->all();

        // Get product type options from enum
        $productTypeOptions = collect(ProductType::cases())->map(fn($case) => [
            'value' => $case->value,
            'label' => ucfirst(str_replace('_', ' ', $case->name)),
        ])->all();

        return Inertia::render('application/index', [
            'applications' => $applications,
            'statusOptions' => $statusOptions,
            'productTypeOptions' => $productTypeOptions,
        ]);
    }

    public function create()
    {
        return Inertia::render('application/create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_id' => 'required|integer',
            'model_type' => 'required|string',
            'application_number' => 'required|string|unique:applications,application_number',
            'approval_step' => 'integer',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            // Add other validation rules as needed
        ]);

        Application::create($validated);

        return redirect()->route('applications.index')->with('success', 'Application created successfully.');
    }

    public function show(Application $application)
    {
        $application->load([
            'model', 
            'model.member', 
            'model.familyMembers',
            'model.grantors',
        ]);

        return Inertia::render('application/show', [
            'application' => $application,
        ]);
    }

    public function edit(Application $application)
    {
        return Inertia::render('application/edit', [
            'application' => $application,
        ]);
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'approval_step' => 'integer',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            // Add other validation rules as needed
        ]);

        $application->update($validated);

        return redirect()->route('applications.index')->with('success', 'Application updated successfully.');
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return redirect()->route('applications.index')->with('success', 'Application deleted.');
    }
}
