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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('/', 'QuizController');
Route::post('/step1', 'QuizController@step1')->name('step1');
Route::post('/step2', 'QuizController@step2')->name('step2');
Route::post('/step3', 'QuizController@step3')->name('step3');
Route::post('/getResult', 'QuizController@getResult')->name('getResult');
Route::post('/refresh_data', 'QuizController@refresh_data')->name('refresh_data');