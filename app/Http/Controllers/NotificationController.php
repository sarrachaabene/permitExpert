<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
  public function index(){
    $notification= Notification::get();
  return response()->json($notification, 200);
  }

  public function ShowNotificationsByReceptientId($ReceptientId)
  {
      $notification = Notification::where('receptient_msg', $ReceptientId)->get();
      return response()->json($notification, 200);
  }



     public function show($id){
      $notification = Notification::find($id);
      if($notification){
    return response()->json($notification, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
}
