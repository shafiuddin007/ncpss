<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Relationship;

class RelationshipController extends Controller
{
    //

    public function list()
    {
        return Inertia::render('relationship/index', [
            'relationships' => Relationship::all(),
        ]);
    }
}
