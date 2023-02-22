<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClusteredPrecinctResult;
use App\Http\Resources\V1\ClusteredPrecinctResultResource;

class ClusteredPrecinctResultController extends Controller
{
    //
    public function index(Request $request)
    {

        // $municipality = implode(',', $request['municipality']);
        // $municipality = strtoupper($municipality);

        // return  ClusteredPrecinctResultResource::collection(
        //     ClusteredPrecinctResult::select('candidate_name')->whereIn('municipality_name', [$request['municipality']])->groupBy('candidate_name')->get()
        // );
        return ClusteredPrecinctResult::select('municipality_name', 'candidate_position', 'candidate_name')
            ->selectRaw("SUM(total_votes) as total_votes")
            ->whereIn('municipality_name', $request['municipality'])
            ->whereIn('candidate_position', $request['position'])
            ->groupBy('municipality_name', 'candidate_position', 'candidate_name')
            ->get();
    }
}
