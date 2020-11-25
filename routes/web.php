<?php

use App\Http\Controllers\ChannelController;
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

Route::get('/', [ChannelController::class, 'index']);

Route::get('/channels/{source}', [ChannelController::class, 'list'])
    ->name('getChannelMapUI');

Route::post('/channels/{source}/map', [ChannelController::class, 'map'])
    ->name('applyChannelMap');

Route::get('/channels/{source}/playlist', [ChannelController::class, 'playlist'])
    ->name('sourcePlaylist');

Route::get('/channels/{source}/xmltv', [ChannelController::class, 'xmltv'])
    ->name('sourceXmlTv');

