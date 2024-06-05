<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Route Group
Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function(){
    Route::get('dashboard','AdminController@dashboard');
    Route::match(['get','post'],'login','AdminController@login');
});
