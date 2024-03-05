<?php

namespace App\Http\Controllers; // Déplacez le namespace ici

use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Controllers\Controller; // Assurez-vous que l'importation est correcte

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::get();
        return response()->json($message, 200);
    }

    public function store(Request $request)
    {
        $message = Message::create($request->all());
        if ($message) { 
            $message->save();
            return response()->json($message, 200);
        }
        return response()->json("message not created", 400);
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
