<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ControlPanelCode;
use App\Models\AccessCode;

class ControlPanelCodeController extends Controller
{
    //
    public function verifyControlCode(Request $request)
    {
        // if (!$request['access_code']) {
        //     return ['success' => false, 'message' => 'Please enter access code'];
        // }


        $result = ControlPanelCode::select('control_code', 'is_active')->where('control_code', $request['control_code'])->get();
        if (count($result)) {
            if ($result[0]['is_active']) {

                return ['success' => true, 'message' => 'access verified', 'data' => $result[0]];
            } else {
                return ['success' => false, 'message' => 'Content is not accessible, please contact your administrator'];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid access code'];
        }
    }

    public function showAccessCode(Request $request)
    {
        $result = ControlPanelCode::select('is_active')->where('control_code', $request['control_code'])->get();
        if (count($result)) {
            if ($result[0]['is_active']) {
                $access_codes = AccessCode::all();
                return ['success' => true, 'message' => 'access verified', 'data' => $access_codes];
            } else {
                return ['success' => false, 'message' => 'Content is not accessible, please contact your administrator'];
            }
        } else {
            return ['success' => false, 'message' => 'Invalid request'];
        }
    }
}
