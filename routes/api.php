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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1'], function () {
    Route::post('access', 'AccessController@getAccess')->middleware('auth:sanctum');
    Route::get('profile', 'ProfileController@index')->middleware('auth:sanctum');
    Route::post('profile/avatar', 'ProfileController@loadAvatar')->middleware('auth:sanctum');
    Route::get('profile/security/two-fa', 'ProfileController@getTwoFaData')->middleware('auth:sanctum');
    Route::post('profile/security/two-fa/verify-code', 'ProfileController@verifyTwoFaCode')->middleware('auth:sanctum');
    Route::put('profile/password', 'ProfileController@changePassword')->middleware('auth:sanctum');


    Route::post('auth/login', 'AuthController@login');
    Route::post('/auth/login/code/check', 'AuthController@checkTwoFaCode');

    Route::get('users', 'UserController@index')->middleware('auth:sanctum');
    Route::get('users/{id}', 'UserController@show')->middleware('auth:sanctum');
    Route::get('users/{id}/tasks', 'TasksController@getUserTasks')->middleware('auth:sanctum');
    Route::get('posts', 'PostsController@index')->middleware('auth:sanctum');//get

    Route::post('users', 'UserController@store')->middleware('auth:sanctum');//add

    Route::post('projects', 'ProjectsController@store')->middleware('auth:sanctum');//create Project
    Route::get('projects', 'ProjectsController@index')->middleware('auth:sanctum');//get Projects
    Route::get('projects/{id}', 'ProjectsController@show')->middleware('auth:sanctum');//get Project id

    Route::get('projects/{id}/tasks/', 'ProjectsController@getTasksByStatuses')->middleware('auth:sanctum');//get Project
    Route::get('projects/{id}/tasks/{task_id}', 'ProjectsController@getTasksById')->middleware('auth:sanctum');//get Project
    Route::post('projects/{id}/tasks', 'ProjectsController@createTask')->middleware('auth:sanctum');//create task
    Route::put('projects/{project_id}/tasks/{task_id}/status', 'ProjectsController@updateTaskStatus')->middleware('auth:sanctum');//get Project

    Route::get('projects/{id}/tasks/{task_id}/messages', 'ChatController@getMessages')->middleware('auth:sanctum');//get Project
    Route::post('projects/{id}/tasks/{task_id}/messages', 'ChatController@newMessage')->middleware('auth:sanctum');//get Project


//    Route::resource('posts', 'PostController@index')->middleware('auth:sanctum');

});
