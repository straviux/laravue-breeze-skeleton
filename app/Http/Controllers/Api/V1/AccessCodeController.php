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
        $result = AccessCode::select('access_code')->where('access_code', $request['access_code'])->get();
        if (count($result)) {
            return ['success' => true, 'message' => 'access verified'];
        } else {
            return ['success' => false, 'message' => 'invalid access code'];
        }
    }
}
