<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thana;
use Illuminate\Http\JsonResponse;

class DistrictController extends Controller
{
    public function getThanas($districtId): JsonResponse
    {
        $thanas = Thana::where('district_id', $districtId)->get(['id', 'thana_name_en as name']);
        return response()->json($thanas);
    }
}
