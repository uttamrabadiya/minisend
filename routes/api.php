<?php

use App\Http\Controllers\V1\EmailController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::post('email', [EmailController::class, 'sendEmail']);
    Route::get('email/{id}', [EmailController::class, 'viewEmail']);
    Route::get('activities', [EmailController::class, 'activities']);
});

