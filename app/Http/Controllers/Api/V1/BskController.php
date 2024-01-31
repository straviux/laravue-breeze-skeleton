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
            ->orderBy('municipality_name', 'asc')
            ->orderBy('barangay_name', 'asc')
            ->orderBy('candidate_position', 'desc')
            ->orderBy('candidate_rank', 'asc')
            ->get()->all();


        $final_arr = [];

        $bgy = $this->groupSimilarValuesInMultidimensionalArray($result, 'barangay_name');
        $muni = $this->groupSimilarValuesInMultidimensionalArray($bgy, 'municipality_name');
        $mappedResults = array_map(function ($row) {
            return [
                'municipality_name' => $row['municipality_name'],
                'barangay_name' => $row['barangay_name'],
                'candidate_position' => $row['candidate_position'],
                'candidate_name' => $row['candidate_name'],
                'candidate_nickname' => $row['candidate_nickname'],
                'candidate_rank' => $row['candidate_rank'],
                'gender' => $row['gender'],
                'proclamation_date' => $row['proclamation_date'],
                'total_votes' => $row['total_votes']
                // Add more fields as needed
            ];
        }, $result);
        $muni = array_map(function ($m) {
            return ['municipality_name' => $m['municipality_name']];
        }, $muni);
        $bgy = array_map(function ($b) {


            return [
                'barangay_name' => $b['barangay_name'], 'municipality_name' => $b['municipality_name'],
                'results' => array_map(function ($res) {
                    return $res;
                }, isset($result) ? $result : [])
            ];
        }, $bgy);

        $tempbgy = [];
        foreach ($bgy as $b) {
            $tempres = [];
            $i = 0;
            foreach ($positions as $position) {


                $tempresult = [];
                foreach ($result as $res) {
                    if ($res['municipality_name'] == $b['municipality_name'] && $res['barangay_name'] == $b['barangay_name'] && $res['candidate_position'] == $position) {
                        $tempresult[] = [
                            'barangay_name' => $res['barangay_name'],
                            'candidate_name' => $res['candidate_name'],
                            'candidate_nickname' => $res['candidate_nickname'],
                            'candidate_position' => $res['candidate_position'],
                            'candidate_rank' => $res['candidate_rank'],
                            'proclamation_date' => $res['proclamation_date'],
                            'total_votes' => $res['total_votes']
                        ];
                    }
                }

                $tempres[$i] = ['position_name' => $position, 'result' => $tempresult];
                $i++;
            }
            $tempbgy[] = ['barangay_name' => $b['barangay_name'], 'municipality_name' => $b['municipality_name'], 'position' => $tempres];
        }

        $tempArr = [];
        foreach ($muni as $m) {
            $tempres = [];
            foreach ($tempbgy as $b) {
                if ($b['municipality_name'] == $m['municipality_name']) {
                    $tempres[] = ['positions' => $b['position'], 'barangay_name' => $b['barangay_name']];
                }
            }

            $tempArr[] = ['municipality_name' => $m['municipality_name'], 'barangays' => $tempres];
        }

        return ['result' => $tempArr];
    }

    public function getBarangayByMunicipality(Request $request)
    {
        $result = BSKResult::select('barangay_name')->distinct()->where('municipality_name', $request['municipality'])->get();
        return $result;
    }

    private function groupSimilarValuesInMultidimensionalArray($array, $column)
    {
        // Extract the specified column values from the array
        $columnValues = array_column($array, $column);

        // Use array_unique to get unique values
        $uniqueValues = array_unique($columnValues);

        // Initialize an empty array to store grouped results
        $groupedArray = [];

        // Loop through unique values and build the grouped array
        foreach ($uniqueValues as $value) {
            // Find all elements in the original array with the current value
            $matchingElements = array_filter($array, function ($element) use ($column, $value) {
                return $element[$column] === $value;
            });

            // Add the first matching element to the grouped array
            $groupedArray[] = reset($matchingElements);
        }

        return $groupedArray;
    }
}
