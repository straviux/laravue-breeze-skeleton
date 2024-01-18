<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BSKResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class BskController extends Controller
{
    public function index(Request $request)
    {
        $municipalities = is_array($request['municipality']) ? $request['municipality'] : [$request['municipality']];
        $barangays = !is_null($request['barangay']) ? (is_array($request['barangay']) ? $request['barangay'] : [$request['barangay']]) : '';
        $positions = !is_null($request['position']) ? (is_array($request['position']) ? $request['position'] : [$request['position']]) : '';



        $result = BSKResult::select(
            'municipality_name',
            'barangay_name',
            'candidate_position',
            'candidate_name',
            'candidate_nickname',
            'candidate_rank',
            'proclamation_date',
            'gender',
            'total_votes'
        )
            ->when($municipalities, function ($query) use ($municipalities) {
                $query->whereIn('municipality_name', $municipalities);
            })
            ->when($barangays, function ($query) use ($barangays) {
                $query->whereIn('barangay_name', $barangays);
            })
            ->when($positions, function ($query) use ($positions) {
                $query->whereIn('candidate_position', $positions);
            })
            // ->selectRaw("SUM(total_votes) as total_votes")
            // ->selectRaw("SUM(total_turnout) as total_turnout")
            // ->selectRaw("SUM(total_invalid) as total_invalid")
            // ->selectRaw("SUM(reg_voters) as reg_voters")
            // ->whereIn('municipality_name', $municipalities)
            // ->whereIn('candidate_position', $positions)
            // ->where('election_year', $ac[0]['election_year'])
            ->orderBy('municipality_name', 'asc')
            ->orderBy('barangay_name', 'asc')
            ->orderBy('candidate_position', 'asc')
            ->orderBy('candidate_rank', 'asc')
            ->get();

        return $result;
    }

    public function getBarangayByMunicipality(Request $request)
    {
        $result = BSKResult::select('barangay_name')->distinct()->where('municipality_name', $request['municipality'])->get();
        return $result;
    }
}
