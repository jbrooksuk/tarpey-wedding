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

Route::get('/', 'AppController@welcome')->name('home');

Route::post('/invite', 'AppController@handleInvite')->name('post:invite');

Route::get('/invite', 'AppController@invite')->name('invite');

Route::post('/rsvp/{family}', 'AppController@rsvp')->name('post:rsvp');

Route::post('/nexmo/receive', NexmoHandler::class);
