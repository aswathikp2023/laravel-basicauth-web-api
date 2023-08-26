<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'api',], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::group(['middleware' => 'istokenvalid'], function() {
        Route::get('admin-logout', [AuthController::class, 'adminlogout']);
        Route::middleware('api')->get('admin/allemployees', [AuthController::class, 'allemployees']);
      });
   
// });