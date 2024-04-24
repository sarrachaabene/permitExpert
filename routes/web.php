<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
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

Route::get('/', function () {
  //if(Auth::check())
  return view('auth.login');
});

Route::get('/dashbord', function () {
  return view('welcome'); 
});


/* Route::middleware(['auth'])->group(function () {
}); */
Auth::routes();

Route::get('/autoEcole', function () {
  return view('welcome'); 
})->middleware('auth');
Route::get('/demande', function () {
  return view('welcome'); 
})->middleware('auth');

Route::get('/utilisateur', function () {
  return view('welcome'); 
})->middleware('auth');
Route::get('/Calendar', function () {
  return view('welcome'); 
})->middleware('auth');

Route::get('/transaction', function () {
  return view('welcome'); 
})->middleware('auth');
Route::get('/vehicule', function () {
  return view('welcome'); 
})->middleware('auth');
Route::get('/profile', function () {
  return view('welcome'); 
})->middleware('auth');
Route::get('/parametre', function () {
  return view('welcome'); 
})->middleware('auth');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/{any}', function () {
  return view('welcome');
})->where('any', '.*');

