<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Grantor;
use App\Models\Member;

class GrantorController extends Controller
{
    public function findGrantor(Request $request, $grantor_id): JsonResponse
    {
        $grantor = Member::find($grantor_id);

        if (!$grantor) {
            return response()->json(['message' => 'Grantor not found'], 404);
        }

        return response()->json($grantor);
    }
}
