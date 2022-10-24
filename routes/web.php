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

Route::get('/', function () {
    return view('welcome');
});

Route::view('registration','registration');
Route::view('login','login');

Route::post('registerUser','App\Http\Controllers\RestoController@registerUser');
Route::post('loginUser','App\Http\Controllers\RestoController@login');

Route::group(['middleware'=>'customAuth'],function(){
    Route::get('/list','App\Http\Controllers\RestoController@list');
    Route::view('/add','add');
    Route::post('addResto','App\Http\Controllers\RestoController@addResto');
    Route::view('register','registration');
    Route::view('login','login');
    Route::get('logout','App\Http\Controllers\RestoController@logout');
});
