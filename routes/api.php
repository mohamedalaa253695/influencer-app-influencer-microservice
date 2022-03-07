<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\ProductController;

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

Route::get('user', [AuthController::class, 'user']);

Route::get('products', [ProductController::class, 'index']);
Route::group([
    'middleware' => 'scope.influencer'
], function () {
    Route::post('links', [LinkController::class, 'store']);
    Route::get('stats', [StatsController::class, 'index']);
    Route::get('rankings', [StatsController::class, 'rankings']);
});
