<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationController extends Controller
{
  public function index(){
    $notification= Notification::get();
  return response()->json($notification, 200);
  }

    



     public function show($id){
      $notification = Notification::find($id);
      if($examen){
    return response()->json($examen, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
}
