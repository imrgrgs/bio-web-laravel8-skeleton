<?php

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

Route::get('/', function () {
    return response()->json([
        'status' => 200,
        'success' => true,
        'message' => 'BIO-Connect API Base vs. 1.0',
        'data' => null
    ], 200);
});

/**
 * Laravel Socialite routes social midia login
 */
// Route::group([
//     'middleware' => '',
//     'prefix' => 'v1/portal/sociallogin'
// ], function ($router) {
//     Route::get('{provider}', 'API\Portal\AuthController@redirectToProvider');
//     Route::get('{provider}/callback', 'API\Portal\AuthController@handleProviderCallback');
// });
//----------------------------------------------------------------------------------------


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
