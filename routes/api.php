<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AutoEcoleController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermisController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ResultatController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers;
use App\Models\User;
use App\Models\AutoEcole;
use App\Models\Seance;
use App\Models\Message;
use App\Http\Controllers\ExamenController;
use App\Models\Examen;
use App\Models\Notification;
use App\Models\Resultat;


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
Route::post('/autoEcole/store',[AutoEcoleController::class,'store']);
Route::get('/autoEcole/findAutoEcoleByUserId/{id}',[AutoEcoleController::class,'showAutoEcoleByUserId']); 

Route::get('/seance/index',[SeanceController::class,'index']);
Route::get('/seance/show/{id}',[SeanceController::class,'show']);
Route::post('/seance/store',[SeanceController::class,'store']);
Route::put('/seance/update/{id}',[SeanceController::class,'update']);
Route::delete('/seance/delete/{id}',[SeanceController::class,'delete']);
Route::post('/seance/AccepterPourCandidat/{id}',[SeanceController::class,'AccepterPourCandidat']);
Route::post('/seance/RefuserPourCandidat/{id}',[SeanceController::class,'RefuserPourCandidat']);
Route::post('/seance/AccepterPourMoniteur/{id}',[SeanceController::class,'AccepterPourMoniteur']);
Route::post('/seance/RefuserPourMoniteur/{id}',[SeanceController::class,'RefuserPourMoniteur']);
Route::post('/seance/updateSeanceStatus/{id}',[SeanceController::class,'updateSeanceStatus']);


Route::get('/Examen/index',[ExamenController::class,'index']);
Route::get('/Examen/show/{id}',[ExamenController::class,'show']);

Route::post('/Examen/store',[ExamenController::class,'store']);
Route::put('/Examen/update/{id}',[ExamenController::class,'update']);
Route::delete('/Examen/delete/{id}',[ExamenController::class,'delete']);
Route::post('/Examen/AccepterExamen/{id}',[ExamenController::class,'AccepterExamen']);
Route::post('/Examen/RefuserExamen/{id}',[ExamenController::class,'RefuserExamen']);

Route::get('/Notification/index',[NotificationController::class,'index']);
Route::get('/Notification/show/{id}',[NotificationController::class,'show']);



Route::get('/message/index',[MessageController::class,'index']);
Route::get('/messgae/show/{id}',[MessageController::class,'show']);
Route::post('/messsage/store',[MessageController::class,'store']);


Route::get('/transaction/index',[TransactionController::class,'index']);
Route::get('/transaction/show/{id}',[TransactionController::class,'show']);
Route::post('/transaction/store',[TransactionController::class,'store']);


Route::get('/permis/index',[PermisController::class,'index']);
Route::get('/permis/show/{id}',[PermisController::class,'show']);
Route::post('/permis/store',[PermisController::class,'store']);
Route::put('/permis/update/{id}',[PermisController::class,'update']);
Route::delete('/permis/delete/{id}',[PermisController::class,'delete']);


Route::get('/vehicule/index',[VehiculeController::class,'index']);
Route::get('/vehicule/show/{id}',[VehiculeController::class,'show']);
Route::post('/vehicule/store',[vehiculeController::class,'store']);
Route::put('/vehicule/update/{id}',[vehiculeController::class,'update']);
Route::delete('/vehicule/delete/{id}',[vehiculeController::class,'delete']);



Route::get('/resultat/index',[ResultatController::class,'index']);
Route::get('/resultat/show/{id}',[ResultatController::class,'show']);
Route::post('/resultat/store',[ResultatController::class,'store']);
Route::put('/resultat/update/{id}',[ResultatController::class,'update']);
Route::delete('/resultat/delete/{id}',[ResultatController::class,'delete']);