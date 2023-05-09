<?php

use App\Http\Controllers\Api\V1\ClusteredPrecinctResultController;
use App\Http\Controllers\Api\V1\AccessCodeController;
use App\Http\Controllers\Api\V1\ControlPanelCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('clustered-precinct-results', ClusteredPrecinctResultController::class);
    // Route::get('/municipality_by_province', ClusteredPrecinctResultController::class, 'getMunicipalityByProvince');
    Route::get('/municipality-by-province', [ClusteredPrecinctResultController::class, 'getMunicipalityByProvince']);
    Route::get('/barangay-by-municipality', [ClusteredPrecinctResultController::class, 'getBarangayByMunicipality']);
    Route::post('/verify-access-code', [AccessCodeController::class, 'verifyAccessCode']);
    Route::post('/verify-control-code', [ControlPanelCodeController::class, 'verifyControlCode']);
    Route::get('/show-access-code', [ControlPanelCodeController::class, 'showAccessCode']);
    Route::post('/update-access-code', [AccessCodeController::class, 'updateAccessCode']);
});
