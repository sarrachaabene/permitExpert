<?php

namespace App\Http\Controllers; // Déplacez le namespace ici

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::get();
        return response()->json($message, 200);
    }

  /*  public function store(Request $request)
    {
        $message = Message::create($request->all());
        // Créer une nouvelle notification associée au message créé
        Notification::create([
          'message_id' => $message->id,
          'message_description' =>$message->description,
          'sender_msg' =>$message->sender_id,
          'receptient_msg'=>$message->recipient_id
        ]);
        return response()->json($message, 200);
    }*/

    public function store(Request $request)
    {   $user = User::find($request->sender_id);

        $message = Message::create($request->all());
        // Créer une nouvelle notification associée au message créé
        $notification=Notification::create([
          'message_id' => $message->id,
          'message_description' =>$message->description,
          'sender_msg' =>$message->sender_id,
          'receptient_msg'=>$message->recipient_id
        ]);
        $user->notification_id = $notification->id;
        $user->save();
        return response()->json($message, 200);
    }

    public function show($id)
    {
        $message = Message::find($id);
        if ($message) {
            return response()->json($message, 200);
        } else {
            $msg = "votre id n'est pas trouve";
            return response()->json($msg, 200);
        }   
    }
}
