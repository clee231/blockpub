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

Route::get('peers', 'PeerController@getPeers');
Route::get('peer/add', 'PeerController@add');
Route::get('peers/invalidate', 'PeerController@invalidate');
