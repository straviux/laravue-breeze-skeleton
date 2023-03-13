<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClusteredPrecinctResult;
// use App\Http\Resources\V1\ClusteredPrecinctResultResource;
use Illuminate\Support\Facades\Http;

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
        if ($request['report_level'] == 'province') {
            $positions = array_diff($positions, array('CONGRESSMAN', 'BOARD MEMBER'));
            $district = array_merge($district1, $district2, $district3);
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

            $cong1 = $this->getAllResultByPosition('CONGRESSMAN', $district1);

            $cong2 = $this->getAllResultByPosition('CONGRESSMAN', $district2);
            $cong3 = $this->getAllResultByPosition('CONGRESSMAN', $district3);
            $bm1 = $this->getAllResultByPosition('BOARD MEMBER', $district1);
            $bm2 = $this->getAllResultByPosition('BOARD MEMBER', $district2);
            $bm3 = $this->getAllResultByPosition('BOARD MEMBER', $district3);
            $jpm_members1 = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_district?district=1ST');
            $jpm_members1 = $jpm_members1->json();
            $jpm_members2 = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_district?district=2ND');
            $jpm_members2 = $jpm_members2->json();
            $jpm_members3 = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_district?district=3RD');
            $jpm_members3 = $jpm_members3->json();
            $election_result[0]['result'] = $final_arr;
            $election_result[0]['result']['turnouts'][] = [
                'position' => 'CONGRESSMAN',
                'district' => [
                    [
                        'id' => 1, 'candidates' => $cong1['candidates'],
                        'position_total_votes' => $cong1['position_total_votes'],
                        'total_turnout' => $cong1['total_turnout'],
                        'reg_voters' => $cong1['reg_voters'],
                        'jpm_members' => $jpm_members1
                    ],
                    [
                        'id' => 2, 'candidates' => $cong2['candidates'],
                        'position_total_votes' => $cong2['position_total_votes'],
                        'total_turnout' => $cong2['total_turnout'],
                        'reg_voters' => $cong2['reg_voters'],
                        'jpm_members' => $jpm_members2
                    ],
                    [
                        'id' => 3, 'candidates' => $cong3['candidates'],
                        'position_total_votes' => $cong3['position_total_votes'],
                        'total_turnout' => $cong3['total_turnout'],
                        'reg_voters' => $cong3['reg_voters'],
                        'jpm_members' => $jpm_members3
                    ]
                ]
            ];
            $election_result[0]['result']['turnouts'][] = [
                'position' => 'BOARD MEMBER',
                'district' => [
                    [
                        'id' => 1, 'candidates' => $bm1['candidates'],
                        'position_total_votes' => $bm1['position_total_votes'],
                        'total_turnout' => $bm1['total_turnout'],
                        'reg_voters' => $bm1['reg_voters'],
                        'jpm_members' => $jpm_members1
                    ],
                    [
                        'id' => 2, 'candidates' => $bm2['candidates'],
                        'position_total_votes' => $bm2['position_total_votes'],
                        'total_turnout' => $bm2['total_turnout'],
                        'reg_voters' => $bm2['reg_voters'],
                        'jpm_members' => $jpm_members2
                    ],
                    [
                        'id' => 3, 'candidates' => $bm3['candidates'],
                        'position_total_votes' => $bm3['position_total_votes'],
                        'total_turnout' => $bm3['total_turnout'],
                        'reg_voters' => $bm3['reg_voters'],
                        'jpm_members' => $jpm_members3
                    ]
                ]
            ];

            $order = ['PRESIDENT', 'V-PRESIDENT', 'CONGRESSMAN', 'GOVERNOR', 'VICE-GOVERNOR', 'BOARD MEMBER', 'SENATOR', 'PARTY LIST'];
            usort($election_result[0]['result']['turnouts'], function ($a, $b) use ($order) {
                $pos_a = array_search($a['position'], $order);
                $pos_b = array_search($b['position'], $order);
                return $pos_a - $pos_b;
            });

            // GET JPM MEMBERS SUMMARY
            $jpm_members = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_province');
            $election_result[0]['jpm_members'] = $jpm_members->json();

            return $election_result;
        } else if ($request['report_level'] == 'district') {
            $district = [];
            $final_arr = [];
            $x = 0;
            if ($request['district'] == 1) {
                $district = $district1;
                $jpm_members = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_district?district=1ST');
            } else if ($request['district'] == 2) {
                $district = $district2;
                $jpm_members = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_district?district=2ND');
            } else if ($request['district'] == 3) {

                $jpm_members = Http::get('http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_district?district=3RD');
                $district = $district3;

                if (in_array('CONGRESSMAN', $positions)) {
                    $cong = $this->getAllResultByPosition('CONGRESSMAN', $district);
                    $final_arr['turnouts'][] = [
                        'position' => 'CONGRESSMAN', 'position_total_votes' => $cong['position_total_votes'], "total_turnout" => $cong['total_turnout'],
                        "candidates" => $cong['candidates']
                    ];
                    $positions = array_diff($positions, array('CONGRESSMAN'));
                }

                if (in_array('BOARD MEMBER', $positions)) {
                    $bm = $this->getAllResultByPosition('BOARD MEMBER', $district);
                    $final_arr['turnouts'][] = [
                        'position' => 'BOARD MEMBER', 'position_total_votes' => $bm['position_total_votes'], "total_turnout" => $bm['total_turnout'],
                        "candidates" => $bm['candidates']
                    ];
                    $positions = array_diff($positions, array('BOARD MEMBER'));
                }

                if (in_array('GOVERNOR', $positions)) {
                    $gov = $this->getAllResultByPosition('GOVERNOR', $district);
                    $final_arr['turnouts'][] = [
                        'position' => 'GOVERNOR', 'position_total_votes' => $gov['position_total_votes'], "total_turnout" => $gov['total_turnout'],
                        "candidates" => $gov['candidates']
                    ];
                    $positions = array_diff($positions, array('GOVERNOR'));
                }

                if (in_array('VICE-GOVERNOR', $positions)) {
                    $vgov = $this->getAllResultByPosition('VICE-GOVERNOR', $district);
                    $final_arr['turnouts'][] = [
                        'position' => 'VICE-GOVERNOR', 'position_total_votes' => $vgov['position_total_votes'], "total_turnout" => $vgov['total_turnout'],
                        "candidates" => $vgov['candidates']
                    ];
                    $positions = array_diff($positions, array('VICE-GOVERNOR'));
                }



                $x = 4; // set next array index to 4 as 4 items is already added
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



            $final_arr['stats'][] =
                [
                    'total_turnout' => $result[0]['total_turnout'],
                    'total_invalid' => $result[0]['total_invalid'],
                    'reg_voters' => $result[0]['reg_voters'],
                    // 'barangay_name' => $temp_arr[0]['barangay_name']
                ];
            // start array at position 2, as 2 items is already
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

            // return $final_arr;
            // $query_params = str_replace("'", "", $query_params);

            // $election_result[0]['district'] = str_replace("'", "", $query_params);
            $election_result[0]['jpm_members'] = $jpm_members->json();
            $election_result[0]['result'] = $final_arr;
            $order = ['PRESIDENT', 'V-PRESIDENT', 'CONGRESSMAN', 'GOVERNOR', 'VICE-GOVERNOR', 'BOARD MEMBER', 'SENATOR', 'PARTY LIST'];
            usort($election_result[0]['result']['turnouts'], function ($a, $b) use ($order) {
                $pos_a = array_search($a['position'], $order);
                $pos_b = array_search($b['position'], $order);
                return $pos_a - $pos_b;
            });
            return $election_result;
        } else if ($request['report_level'] == 'municipality') {
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
            $jpm_members_url = 'http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_municipality?municipalities[]=';
        } else if ($request['report_level'] == 'barangay') {
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
            $jpm_members_url = 'http://assistance.jpmpalawan.org/mobi/jpm/ajax_get_member_summary_by_barangay?municipality=' . $municipalities[0] . '&barangays[]=';
        }

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


            // $query_params = str_replace("'", "", $query_params);
            $jpm_members = Http::get($jpm_members_url .  $gl);

            $election_result[$i]['jpm_members'] = $jpm_members->json();
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

    private function getAllResultByPosition($position = null, $municipalities = [])
    {

        $result =  ClusteredPrecinctResult::select('candidate_name', 'total_invalid')
            ->selectRaw("SUM(total_votes) as total_votes")
            ->selectRaw("SUM(total_turnout) as total_turnout")
            ->selectRaw("SUM(total_invalid) as total_invalid")
            ->selectRaw("SUM(reg_voters) as reg_voters")
            ->where('candidate_position', $position)
            ->when($municipalities, function ($query) use ($municipalities) {
                $query->whereIn('municipality_name', $municipalities);
            })
            ->groupBy('candidate_name', 'candidate_position')
            ->get();
        $final_arr = [];
        $temp_arr = [];
        $x = 0;
        $final_arr['position_total_votes'] = 0;
        foreach ($result as $res) {
            $temp_arr[$x] = $res;
            $final_arr['position_total_votes'] += $res['total_votes'];
            $final_arr['total_turnout'] = $res['total_turnout'];
            $final_arr['reg_voters'] = $res['reg_voters'];
            $x++;
        }
        $final_arr['candidates'] = $temp_arr;
        return $final_arr;
    }
}
