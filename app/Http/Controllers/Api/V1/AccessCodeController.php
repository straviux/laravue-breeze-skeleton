<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessCode;

class AccessCodeController extends Controller
{
    //
    public function verifyAccessCode(Request $request)
    {
        // if (!$request['access_code']) {
        //     return ['success' => false, 'message' => 'Please enter access code'];
        // }


        $result = AccessCode::select('access_code', 'province', 'municipality', 'is_accessible', 'visit_count')->where('access_code', $request['access_code'])->get();
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
}
