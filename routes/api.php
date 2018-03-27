<?php

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


$baseNamespace = 'Api\Controllers\\';

$users = $baseNamespace . 'UserController';

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'auth'], function () use ($users) {

    Route::middleware(['auth.api'])->group(function () use ($users) {

        // POST - api/auth/login
        Route::post('login', $users . '@login');

    });

});

/*
|--------------------------------------------------------------------------
| USER Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'users', 'middleware' => 'auth.jwt'], function () use ($users) {

    // POST - api/users
    Route::post('', $users . '@create');

    // GET - api/users
    Route::get('', $users . '@getAll');

    // GET - api/users/count
    Route::get('count', $users . '@getCountByFilter');

    // GET - api/users/current
    Route::get('current', $users . '@getCurrentUser');

    // GET - api/users/{id}
    Route::get('{id}', $users . '@get');

    // PUT - api/users/{id}
    Route::put('{id}', $users . '@update');

    // DELETE - api/users/{id}
    Route::delete('{id}', $users . '@delete');

});