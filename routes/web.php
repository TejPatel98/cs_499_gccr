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
})->name('home');

Route::view('/stock', 'stocks/select')->name('stock.select');

Route::view('/results', 'results/results')->name('stock.results');

Route::post('/stock/submit', 'stocks@submitSelection')->name('stock.submit');
