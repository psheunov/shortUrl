<?php

use App\Http\Controllers\LinkController;
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


Route::group(['prefix' => '/links'], function () {
    Route::post('/', [LinkController::class, 'store']);

    Route::delete('/{link:id}', [LinkController::class, 'delete']);

    Route::patch('/{link:id}', [LinkController::class, 'patch']);

    Route::get('/', [LinkController::class, 'links']);
    Route::get('/{id}', [LinkController::class, 'id']);
});

Route::group(['prefix' => 'stats',], function () {
    Route::get('/', [LinkController::class, 'visitorsStatistics']);
    Route::get('/{link:id}', [LinkController::class, 'getStatistic']);
});

Route::get('/{link:short_url}', [LinkController::class, 'redirect']);
