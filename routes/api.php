<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use   App\Http\Controllers\ApiController;
use App\Models\User;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/hello', function (Request $request) {
  return response()->json("hello", 200);
});
Route::get('/user/index',[ApiController::class,'index']);
Route::get('/user/show/{id}',[ApiController::class,'show']);
Route::post('/user/store',[ApiController::class,'store']);
Route::put('/user/update/{id}',[ApiController::class,'update']);
Route::delete('/user/delete/{id}',[ApiController::class,'delete']);
