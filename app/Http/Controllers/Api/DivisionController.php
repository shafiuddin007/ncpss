<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\JsonResponse;

class DivisionController extends Controller
{
    public function getDistricts($divisionId): JsonResponse
    {
        $districts = District::where('division_id', $divisionId)->get(['id', 'district_name_en as name']);
        return response()->json($districts);
    }
}
