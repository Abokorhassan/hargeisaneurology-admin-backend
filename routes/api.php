<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// // Register Route
// Route::post('register', 'UserController@register');

// // Login Route
// Route::post('login', 'UserController@login');

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {

    // Register Route
    Route::post('register', 'UserController@register');

    // Login Route
    Route::post('login', 'UserController@login');

    // getting the authenticated user
    Route::post('me', 'UserController@me');

    // Log out Route
    Route::post('logout', 'UserController@logout');

    // refreshing the token
    Route::post('refresh', 'UserController@refresh');
});

//On Unauthorized Login
Route::get('error', function () {
    return response()->json(['error' => 'Invalid Token'], 401);
})->name('login');
