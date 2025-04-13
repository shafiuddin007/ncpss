<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Member;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function list(): Response
    {
        
        return Inertia::render('member/index', [
            'members' => Member::all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('member/create', [
            'countries' => Country::all()->map(function ($country) {
                return ['id' => $country->id, 'name' => $country->name];
            }),
        ]);
    }   

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'dob' => 'required|date',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,others',
            'nationality' => 'required|string|max:255',
            'nid' => 'required|string|max:20',
            'religion' => 'required|string|max:255',
            'blood_group' => 'required|string|max:5',
            'marital_status' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'guardian_occuption' => 'nullable|string|max:255',
            'educational_level' => 'required|string|max:255',
            'pre_address' => 'required|string|max:255',
            'pre_division' => 'required|string|max:255',
            'pre_district' => 'required|string|max:255',
            'pre_thana' => 'required|string|max:255',
            'pre_post_code' => 'required|string|max:10',
            'per_address' => 'required|string|max:255',
            'per_division' => 'required|string|max:255',
            'per_district' => 'required|string|max:255',
            'per_thana' => 'required|string|max:255',
            'per_post_code' => 'required|string|max:10',
        ]);

        Member::create($validated);

        return Inertia::render('member/success', [
            'message' => 'Member added successfully.',
            'redirectUrl' => route('members.list'),
        ]);
    }
}
