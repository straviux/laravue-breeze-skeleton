<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessCode;
use App\Models\AccessHistory;
use Carbon\Carbon;

class AccessCodeController extends Controller
{
    //
    public function verifyAccessCode(Request $request)
    {
        // if (!$request['access_code']) {
        //     return ['success' => false, 'message' => 'Please enter access code'];
        // }


        $result = AccessCode::select('access_code', 'province', 'municipality', 'is_accessible', 'visit_count', 'last_visited', 'election_year')
            ->where('access_code', $request['access_code'])->get();
        if (count($result)) {
            if ($result[0]['is_accessible']) {
                $date_now = Carbon::now();
                $d1 = Carbon::createFromFormat('Y-m-d H:i:s', $date_now)->format('Y-m-d');
                $d2 = Carbon::createFromFormat('Y-m-d H:i:s', $result[0]['last_visited'])->format('Y-m-d');
                if ($d1 != $d2) {
                    AccessCode::where('access_code', $request['access_code'])->increment('visit_count', 1, ['last_visited' => $date_now]);
                }

                return ['success' => true, 'message' => 'access verified', 'data' => $result[0]];
            } else {
                return ['success' => false, 'message' => 'Content is not accessible, please contact your administrator'];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid access code'];
        }
    }

    public function updateAccessCode(Request $request)
    {
        $result = AccessCode::where('access_code', $request['access_code'])->update(['is_accessible' => $request['is_accessible']]);
        if ($result) {
            return ['success' => true, 'message' => 'record updated', 'is_accessible' => $request['is_accessible']];
        } else {
            return ['success' => false, 'message' => 'Invalid request'];
        }
    }

    public function showAccessHistory(Request $request)
    {
        $result = AccessHistory::select('access_at')->where('access_code_id', $request['access_code_id'])->orderBy('access_at', 'DESC')->get();
        $ac = AccessCode::select('visit_count')->where('id', $request['access_code_id'])->get();
        if (count($result)) {
            return ['success' => true, 'message' => 'access verified', 'result' => $result, 'visit_count' => $ac[0]['visit_count']];
        } else {
            return ['success' => false, 'message' => 'Invalid request'];
        }
    }
}
