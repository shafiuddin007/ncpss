<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Division;
use App\Models\Member;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    public function list(): Response
    {

        return Inertia::render('member/index', [
            'members' => Member::all()->where('is_deleted', false),
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

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'dob' => 'required',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|string|in:Male,Female,Others',
            'religion' => 'required|string|max:255',
            'blood_group' => 'string|max:5',
            'marital_status' => 'required|string',
            'nationality' => 'required|string|max:255',
            'nid' => 'required|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'educational_level' => 'nullable|string|max:255',

            // Address fields
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

            // Previous member fields
            'is_previous_member' => 'nullable|boolean',
            'previous_member_number' => 'nullable|string|max:255',

            'profile_image' => 'file|mimes:jpeg,png,jpg|max:2048',
            'signature_image' => 'required|file|mimes:jpeg,png,jpg|max:2048',

            // Nominee fields
            'nominee_name' => 'required|string|max:255',
            'nominee_nid' => 'required|string|max:20',
            'nominee_relationship' => 'required|string|max:255',
            'nominee_age' => 'nullable|integer|min:0',
            'contact_number' => 'required|string|max:15',
            'nominee_address' => 'required|string|max:255',

            // Introducer fields
            'introducer_name' => 'required|string|max:255',
            'introducer_account_number' => 'required|string|max:20',
            'introducer_signature' => 'required|file|max:2048',
            'introducer_date' => 'required|date',
            'acknowledgement' => 'accepted',
        ]);

        $validated['dob'] = \Carbon\Carbon::createFromFormat('Y-m-d', $validated['dob'])->format('Y-m-d');

        try {
            DB::beginTransaction();

            if ($request->hasFile('introducer_signature')) {
                $validated['introducer_signature'] = $request->file('introducer_signature')->store('signatures', 'public');
            }

            if ($request->hasFile('profile_image')) {
                $validated['photo'] = $request->file('profile_image')->store('profile_images', 'public');
            }

            if ($request->hasFile('signature_image')) {
                $validated['signature'] = $request->file('signature_image')->store('signature_images', 'public');
            }

            // Set is_previous_member and previous_member_number
            $validated['is_previous_member'] = $request->input('is_previous_member', false);

            // Assign created_by field from the logged-in user's username
            $validated['created_by'] = auth()->user()->email;
            $validated['updated_by'] = auth()->user()->email;

            // Create the member
            $member = Member::create($validated);

            // Create the nominee
            $member->nominees()->create([
                'nid_birth_no' => $validated['nominee_nid'],
                'nominee_name' => $validated['nominee_name'],
                'relationship' => $validated['nominee_relationship'],
                'age' => $validated['nominee_age'],
                'contact_no' => $validated['contact_number'],
                'address' => $validated['nominee_address'],
            ]);

            // Create the introducer
            $member->introducer()->create([
                'name' => $validated['introducer_name'],
                'account_number' => $member->id,
                'signature' => $validated['introducer_signature'],
                'date' => $validated['introducer_date'],
            ]);

            DB::commit();

            return Inertia::render('member/index', [
                'message' => 'Member added successfully.',
                'members' => Member::all(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Inertia::render('member/error', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id): Response
    {
        $member = Member::findOrFail($id);
        
        return Inertia::render('member/show', [
            'member' => $member,
        ]);
    }

    public function update(Request $request, $id): Response
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'dob' => 'required|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|string|in:male,female,others',
            'religion' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:5',
            'nid' => 'required|string|max:20',
        ]);

        $member->update($validated);

        return Inertia::render('member/index', [
            'message' => 'Member updated successfully.',
            'members' => Member::all(),
        ]);
    }

    public function edit($id): Response
    {
        $member = Member::findOrFail($id);

        return Inertia::render('member/edit', [
            'member' => $member,
        ]);
    }

    public function destroy($id): JsonResponse
    {
        try {
            $member = Member::findOrFail($id);

            if ($member->is_deleted) {
                return response()->json(['message' => 'Member already marked as deleted.'], 400);
            }

            $member->update([
                'is_deleted' => true,
                'updated_by' => auth()->user()->email,
            ]);

            return response()->json(['message' => 'Member marked as deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete the member.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
