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

Route::middleware('auth')->group(function() {
    Route::post('cards/{id}/sort', 'Api\CardController@sort');
    Route::resource('columns', 'Api\KanbanColumnController')->except(['create', 'edit', 'show']);
    Route::resource('cards', 'Api\CardController')->except(['create', 'edit', 'show', 'index']);
});

