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
        $municipalities = is_array($request['municipality']) ? $request['municipality'] : [$request['municipality']];
        $barangays = is_array($request['barangay']) ? $request['barangay'] : [$request['barangay']];
        $positions = is_array($request['position']) ? $request['position'] : [$request['position']];
        $district1 = [
            "AGUTAYA",
            "ARACELI",
            "BUSUANGA",
            "CAGAYANCILLO",
            "CORON",
            "CULION",
            "CUYO",
            "DUMARAN",
            "EL NIDO (BACUIT)",
            "KALAYAAN",
            "LINAPACAN",
            "MAGSAYSAY",
            "ROXAS",
            "SAN VICENTE",
            "TAYTAY"
        ];

        $district2 = [
            "BALABAC",
            "BATARAZA",
            "BROOKE'S POINT",
            "NARRA",
            "QUEZON",
            "RIZAL (MARCOS)",
            "SOFRONIO ESPAÑOLA"
        ];

        $district3 = ["ABORLAN", "PUERTO PRINCESA CITY"];
        // return $request;
        // return  ClusteredPrecinctResultResource::collection(
        //     ClusteredPrecinctResult::select('candidate_name')->whereIn('municipality_name', [$request['municipality']])->groupBy('candidate_name')->get()
        // );
        $geographical_level = [];
        $election_result = [];
        $result = [];
        if ($request['report_level'] == 'district') {
            $district = [];
            if ($request['district'] == 1) {
                $district = $district1;
            } else if ($request['district'] == 2) {
                $district = $district2;
            } else if ($request['district'] == 3) {
                $district = $district3;
            }
            $result = ClusteredPrecinctResult::select('candidate_position', 'candidate_name',  'total_invalid', 'reg_voters')
                ->selectRaw("SUM(total_votes) as total_votes")
                ->selectRaw("SUM(total_turnout) as total_turnout")
                ->selectRaw("SUM(total_invalid) as total_invalid")
                ->selectRaw("SUM(reg_voters) as reg_voters")
                ->whereIn('municipality_name', $district)
                ->whereIn('candidate_position', $positions)
                ->groupBy('candidate_position', 'candidate_name')
                ->get();

            $final_arr = [];
            $final_arr['stats'][] =
                [
                    'total_turnout' => $result[0]['total_turnout'],
                    'total_invalid' => $result[0]['total_invalid'],
                    'reg_voters' => $result[0]['reg_voters'],
                    // 'barangay_name' => $temp_arr[0]['barangay_name']
                ];
            $x = 0;
            foreach ($positions as $position) {
                $final_arr['turnouts'][$x]['position'] = $position;
                $final_arr['turnouts'][$x]['position_total_votes'] = 0;
                foreach ($result as $arr) {

                    if ($arr['candidate_position'] == $position) {
                        // $final_arr['turnouts'][$x]['barangay_name'] = $temp_arr['barangay_name'];
                        $final_arr['turnouts'][$x]['candidates'][] = ['candidate_name' => $arr['candidate_name'], 'total_votes' => $arr['total_votes']];
                        $final_arr['turnouts'][$x]['position_total_votes'] += $arr['total_votes'];
                    }
                }
                $x++;
            }
            $election_result[]['result'] = $final_arr;
            return $election_result;
            // $election_result[$i]['municipality'] = $temp_arr[0]['municipality_name'];
            // $election_result[$i]['barangay'] = $temp_arr[0]['barangay_name'];
            // $election_result[$i]['result'] = $final_arr;
            // $i++;
        }
        if ($request['report_level'] == 'municipality') {
            $result = ClusteredPrecinctResult::select('municipality_name', 'candidate_position', 'candidate_name', 'barangay_name', 'total_invalid', 'reg_voters')
                ->selectRaw("SUM(total_votes) as total_votes")
                ->selectRaw("SUM(total_turnout) as total_turnout")
                ->selectRaw("SUM(total_invalid) as total_invalid")
                ->selectRaw("SUM(reg_voters) as reg_voters")
                ->whereIn('municipality_name', $municipalities)
                ->whereIn('candidate_position', $positions)
                ->groupBy('municipality_name', 'candidate_position', 'candidate_name')
                ->get();
            $geographical_level = $municipalities;
        }
        if ($request['report_level'] == 'barangay') {
            $result = ClusteredPrecinctResult::select('municipality_name', 'candidate_position', 'candidate_name', 'barangay_name', 'total_invalid', 'reg_voters')
                ->selectRaw("SUM(total_votes) as total_votes")
                ->selectRaw("SUM(total_turnout) as total_turnout")
                ->selectRaw("SUM(total_invalid) as total_invalid")
                ->selectRaw("SUM(reg_voters) as reg_voters")
                ->whereIn('municipality_name', $municipalities)
                ->whereIn('barangay_name', $barangays)
                ->whereIn('candidate_position', $positions)
                ->groupBy('municipality_name', 'candidate_position', 'candidate_name', 'barangay_name')
                ->get();
            $geographical_level = $barangays;
        }

        // return $result;
        // return $result;


        $i = 0;

        foreach ($geographical_level as $gl) {
            $temp_arr = [];
            foreach ($result as $res) {

                if ($res['municipality_name'] == $gl) {
                    $temp_arr[] = $res;
                } elseif ($res['barangay_name'] == $gl) {
                    $temp_arr[] = $res;
                }

                // if ($request['report_level'] == 'barangay') {
                //     $temp_arr
                // }
            }

            $final_arr = [];
            $final_arr['stats'][] =
                [
                    'total_turnout' => $temp_arr[0]['total_turnout'],
                    'total_invalid' => $temp_arr[0]['total_invalid'],
                    'reg_voters' => $temp_arr[0]['reg_voters'],
                    // 'barangay_name' => $temp_arr[0]['barangay_name']
                ];
            $x = 0;
            foreach ($positions as $position) {
                $final_arr['turnouts'][$x]['position'] = $position;
                $final_arr['turnouts'][$x]['position_total_votes'] = 0;
                foreach ($temp_arr as $arr) {

                    if ($arr['candidate_position'] == $position) {
                        // $final_arr['turnouts'][$x]['barangay_name'] = $temp_arr['barangay_name'];
                        $final_arr['turnouts'][$x]['candidates'][] = ['candidate_name' => $arr['candidate_name'], 'total_votes' => $arr['total_votes']];
                        $final_arr['turnouts'][$x]['position_total_votes'] += $arr['total_votes'];
                    }
                }
                $x++;
            }
            $election_result[$i]['municipality'] = $temp_arr[0]['municipality_name'];
            $election_result[$i]['barangay'] = $temp_arr[0]['barangay_name'];
            $election_result[$i]['result'] = $final_arr;
            $i++;
        }
        return $election_result;
    }

    public function getBarangayByMunicipality(Request $request)
    {
        $result = ClusteredPrecinctResult::select('barangay_name')->distinct()->where('municipality_name', $request['municipality'])->get();
        return $result;
    }
}
