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
        $result = ClusteredPrecinctResult::select('municipality_name', 'candidate_position', 'candidate_name', 'total_invalid', 'reg_voters')
            ->selectRaw("SUM(total_votes) as total_votes")
            ->selectRaw("SUM(total_turnout) as total_turnout")
            ->selectRaw("SUM(total_invalid) as total_invalid")
            ->selectRaw("SUM(reg_voters) as reg_voters")
            ->whereIn('municipality_name', $request['municipality'])
            ->whereIn('candidate_position', $request['position'])
            ->groupBy('municipality_name', 'candidate_position', 'candidate_name')
            ->get();

        // return $result;
        $election_result = [];

        $i = 0;
        foreach ($request['municipality'] as $municipality) {
            $temp_arr = [];
            foreach ($result as $res) {
                if ($res['municipality_name'] == $municipality) {
                    $temp_arr[] = $res;
                }
            }

            $final_arr = [];
            $final_arr['stats'][] = ['total_turnout' => $temp_arr[0]['total_turnout'], 'total_invalid' => $temp_arr[0]['total_invalid'], 'reg_voters' => $temp_arr[0]['reg_voters']];
            $x = 0;
            foreach ($request['position'] as $position) {
                $final_arr['turnouts'][$x]['position'] = $position;

                foreach ($temp_arr as $arr) {

                    if ($arr['candidate_position'] == $position) {
                        $final_arr['turnouts'][$x]['candidates'][] = ['candidate_name' => $arr['candidate_name'], 'total_votes' => $arr['total_votes']];
                    }
                }
                $x++;
            }
            $election_result[$i]['municipality'] = $municipality;
            $election_result[$i]['result'] = $final_arr;
            $i++;
        }
        return $election_result;
    }
}
