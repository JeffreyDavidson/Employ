<?php

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

// Allow all authentication routes except for register routing.
Auth::routes(['register' => false]);

Route::middleware(['middleware' => 'auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('companies', 'CompaniesController');
});
