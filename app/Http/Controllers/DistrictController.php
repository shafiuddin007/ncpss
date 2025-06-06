<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thana;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
    //
    public function getThanas($districtId): JsonResponse
    {
        $thanas = Thana::where('district_id', $districtId)->get(['id', 'thana_name_en as name']);
        return response()->json($thanas);
    }
}
