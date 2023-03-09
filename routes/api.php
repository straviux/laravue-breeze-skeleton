<?php

use App\Http\Controllers\Api\V1\ClusteredPrecinctResultController;
use App\Http\Controllers\Api\V1\AccessCodeController;
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
    // Route::get('barangay_by_municipality', ClusteredPrecinctResultController::class, 'getBarangayByMunicipality');
    Route::get('/barangay-by-municipality', [ClusteredPrecinctResultController::class, 'getBarangayByMunicipality']);
    Route::post('/verify-access-code', [AccessCodeController::class, 'verifyAccessCode']);
});
