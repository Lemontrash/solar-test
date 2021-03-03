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

Route::post('comment', 'CommentControllerR@store');
Route::get('comment', 'CommentControllerR@index');
Route::get('post/{postId}/comment', 'CommentControllerR@indexByPost');
Route::post('comment/{id}', 'CommentControllerR@update');
Route::delete('comment/{id}', 'CommentControllerR@destroy');

