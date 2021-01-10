<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Laravel Socialite routes social midia login
 */
// Route::group([
//     'prefix' => 'portal/sociallogin'
// ], function ($router) {
//     Route::get('{provider}', 'API\Portal\AuthController@redirectToProvider');
//     Route::get('{provider}/callback', 'API\Portal\AuthController@handleProviderCallback');
// });

//------------------------------------------------------------------------------------------


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
