<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group(['middleware' => 'web'], function () {
// Route::auth();
	Auth::routes([
  'register' => false, // Registration Routes...
  'reset' => false, // Password Reset Routes...
  //'verify' => false, // Email Verification Routes...
]);
Route::get('/', function () {
  return redirect(route('login'));
});

Route::group(['middleware' => 'web'], function () {

Route::get('/home', [CompanyController::class, 'index'])->name('home');
 Route::resource('company', CompanyController::class);
  Route::resource('employee', EmployeeController::class);


    });
});


