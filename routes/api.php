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
use App\Http\Controllers\DemandeInscriptionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RessourceEducativeController;
use App\Http\Controllers;
use App\Models\User;
use App\Models\AutoEcole;
use App\Models\Seance;
use App\Models\Message;
use App\Http\Controllers\ExamenController;
use App\Models\Examen;
use App\Models\Notification;



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
Route::post('/login', [ApiController::class, 'loginClient']);
Route::put('/updateProfile/{email}',[ApiController::class,'registerClient']);

/* Route::middleware('auth')->group(function () { */

  Route::get('/user/index', [ApiController::class, 'index']);
  Route::get('/user/show/{id}', [ApiController::class, 'show']);
  Route::put('/user/update/{id}',[ApiController::class,'update']);
  Route::delete('/user/delete/{id}',[ApiController::class,'delete']);
  Route::post('/user/store',[ApiController::class,'store']);
  Route::get('/user/indexForSuper', [ApiController::class, 'indexForSuper']);
  
  Route::post('/autoEcole/store',[AutoEcoleController::class,'store']);
  Route::get('/autoEcole/findAutoEcoleByUserId/{id}',[AutoEcoleController::class,'showAutoEcoleByUserId']); 
  Route::get('/autoEcole/index',[AutoEcoleController::class,'index']);
  Route::get('/autoEcole/user',[AutoEcoleController::class,'getUsersAutoEcole']);
  Route::get('/autoEcole/show/{id}',[AutoEcoleController::class,'show']);
  Route::put('/autoEcole/update/{id}',[AutoEcoleController::class,'update']);
  Route::delete('/autoEcole/delete/{id}',[AutoEcoleController::class,'delete']);

  Route::get('/seance/index',[SeanceController::class,'index']);
  Route::get('/seance/show/{id}',[SeanceController::class,'show']);
  Route::get('/seance/ShowSeanceBycandidatId/{id}',[SeanceController::class,'ShowSeanceBycandidatId']);
  Route::get('/seance/ShowSeanceByvehiculeId/{id}',[SeanceController::class,'ShowSeanceByvehiculeId']);
  Route::get('/seance/ShowSeanceBymoniteurId/{id}',[SeanceController::class,'ShowSeanceBymoniteurId']);

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
  Route::get('/Examen/ShowExamensBycandidatId/{id}',[ExamenController::class,'ShowExamensBycandidatId']);
  Route::get('/Examen/ShowExamensByvehiculeId/{id}',[ExamenController::class,'ShowExamensByvehiculeId']);

  Route::post('/Examen/store',[ExamenController::class,'store']);
  Route::put('/Examen/update/{id}',[ExamenController::class,'update']);
  Route::delete('/Examen/delete/{id}',[ExamenController::class,'delete']);
  Route::post('/Examen/AccepterExamen/{id}',[ExamenController::class,'AccepterExamen']);
  Route::post('/Examen/RefuserExamen/{id}',[ExamenController::class,'RefuserExamen']);

  Route::get('/Notification/index',[NotificationController::class,'index']);
  Route::get('/Notification/show/{id}',[NotificationController::class,'show']);
  Route::get('/Notification/ShowNotificationsByReceptientId/{id}',[NotificationController::class,'ShowNotificationsByReceptientId']);


  Route::get('/message/index',[MessageController::class,'index']);
  Route::get('/message/show/{id}',[MessageController::class,'show']);
  Route::post('/message/store',[MessageController::class,'store']);


  Route::get('/transaction/index',[TransactionController::class,'index']);
  Route::get('/transaction/show/{id}',[TransactionController::class,'show']);
  Route::post('/transaction/store',[TransactionController::class,'store']);
  Route::delete('/transaction/delete/{id}',[TransactionController::class,'delete']);
  Route::put('/transaction/update/{id}',[TransactionController::class,'update']);
  Route::get('/transaction/ShowTransactionByuserId/{id}',[TransactionController::class,'ShowTransactionByuserId']);
  Route::get('/transaction/ShowTransactionByautoecoleId/{id}',[TransactionController::class,'ShowTransactionByautoecoleId']);
  Route::get('/transaction/ShowTransactionByvehiculeId/{id}',[TransactionController::class,'ShowTransactionByvehiculeId']);


  Route::get('/ressourceeducative/index',[RessourceEducativeController::class,'index']);
  Route::get('/ressourceeducative/show/{id}',[RessourceEducativeController::class,'show']);
  Route::post('/ressourceeducative/store',[RessourceEducativeController::class,'store']);
  Route::put('/ressourceeducative/update/{id}',[RessourceEducativeController::class,'update']);
  Route::delete('/ressourceeducative/delete/{id}',[RessourceEducativeController::class,'delete']);


  Route::get('/vehicule/index',[VehiculeController::class,'index']);
  Route::get('/vehicule/show/{id}',[VehiculeController::class,'show']);
  Route::post('/vehicule/store',[vehiculeController::class,'store']);
  Route::put('/vehicule/update/{id}',[vehiculeController::class,'update']);
  Route::delete('/vehicule/delete/{id}',[vehiculeController::class,'delete']);


  Route::get('/demandeInscript/index',[DemandeInscriptionController::class,'index']);
  Route::get('/demandeInscript/show/{id}',[DemandeInscriptionController::class,'show']);
  Route::post('/demandeInscript/store',[DemandeInscriptionController::class,'store']);
  Route::put('/demandeInscript/update/{id}',[DemandeInscriptionController::class,'update']);
  Route::delete('/demandeInscript/delete/{id}',[DemandeInscriptionController::class,'delete']);
//});