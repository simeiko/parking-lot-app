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


/**
 * Tickets Routes.
 */
Route::post('/tickets', 'TicketsController@create');

Route::get('/tickets', 'TicketsController@count');

Route::get('/tickets/{ticket_id}','TicketsController@show')
    ->where('ticket_id', '^\d+$');


/**
 * Payments Routes.
 */
Route::post('/payments/{ticket_id}', 'PaymentsController@pay')
    ->where('ticket_id', '^\d+$');