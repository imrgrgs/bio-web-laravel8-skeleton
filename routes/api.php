<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
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

$auth = [
    'prefix' => '',
    'domain' => '',
    'middleware' => 'api',
    'as' => 'auth.',
    'namespace' => 'Auth',
];
Route::group($auth, function () {

    Route::post(
        'login',
        [
            'as' => 'login',
            'uses' => 'AuthController@login',
        ]
    );

    Route::post(
        'logout',
        [
            'as' => 'logout',
            'uses' => 'AuthController@logout',
        ]
    );

    Route::post(
        'refresh',
        [
            'as' => 'refresh',
            'uses' => 'AuthController@refresh',
        ]
    );

    Route::post(
        'me',
        [
            'as' => 'me',
            'uses' => 'AuthController@me',
        ]
    );
}); // end group auth






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
    return new UserResource($request->user());
});

$default = [
    'prefix' => '',
    'domain' => '',
    'middleware' => 'auth:api',
    'as' => '',
];
Route::group($default, function () {
    Route::resource('servers', ServerAPIController::class);
});



Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'success' => false,
        'message' => 'Page Not Found. If error persists, contact ti@bitio.com.br',
        'data' => null
    ], 404);
});
