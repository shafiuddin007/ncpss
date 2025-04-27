<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Division;
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
            'divisions' => Division::all()->map(function ($division) {
                return ['id' => $division->id, 'name' => $division->division_name_en];
            }),
        ]);
    }   

    public function store(Request $request): Response
    {

        dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'email|max:255',
            'dob' => 'required|date_format:d/m/Y',
            'place_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,others',
            'nationality' => 'required|string|max:255',
            'nid' => 'required|string|max:20',
            'religion' => 'required|string|max:255',
            'blood_group' => 'required|string|max:5',
            'marital_status' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'guardian_name' => 'required|string|max:255',
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
            'introducer_name' => 'required|string|max:255',
            'introducer_account_number' => 'required|string|max:20',
            'introducer_signature' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'introducer_date' => 'required|date',
            'acknowledgement' => 'accepted',
            'previous_member_number' => 'nullable|string|max:20',
            'profile_image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'signature_image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['dob'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['dob'])->format('Y-m-d');

        if ($request->hasFile('introducer_signature')) {
            $validated['introducer_signature'] = $request->file('introducer_signature')->store('signatures', 'public');
        }

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        if ($request->hasFile('signature_image')) {
            $validated['signature_image'] = $request->file('signature_image')->store('signature_images', 'public');
        }

        Member::create($validated);

        return Inertia::render('member/success', [
            'message' => 'Member added successfully.',
            'redirectUrl' => route('members.list'),
        ]);
    }
}
