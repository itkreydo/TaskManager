<?php

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
//logout with cookie auth
Route::post('api/v1/auth/logout', 'Api\V1\AuthController@logout')->middleware('auth:sanctum');

Route::get('storage/avatars/{filename}', 'FileController@avatars');
Route::get('key', 'FileController@key');

Route::get('{any?}', function () {
    return view('welcome');
})->where('any', '.*');
