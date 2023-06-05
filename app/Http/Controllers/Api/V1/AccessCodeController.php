<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessCode;
use App\Models\AccessHistory;

class AccessCodeController extends Controller
{
    //
    public function verifyAccessCode(Request $request)
    {
        // if (!$request['access_code']) {
        //     return ['success' => false, 'message' => 'Please enter access code'];
        // }


        $result = AccessCode::select('access_code', 'province', 'municipality', 'is_accessible', 'visit_count')
            ->where('access_code', $request['access_code'])->get();
        if (count($result)) {
            if ($result[0]['is_accessible']) {

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
            return ['success' => true, 'message' => 'record updated'];
        } else {
            return ['success' => false, 'message' => 'Invalid request', 'data' => $request['access_code']];
        }
    }

    public function showAccessHistory(Request $request)
    {
        $result = AccessHistory::select('access_at')->where('access_code_id', $request['access_code_id'])->orderBy('access_at', 'DESC')->get();
        if (count($result)) {
            return ['success' => true, 'message' => 'access verified', 'data' => $result];
        } else {
            return ['success' => false, 'message' => 'Invalid request'];
        }
    }
}
