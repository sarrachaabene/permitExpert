<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;

class AutoEcoleController extends Controller
{
  public function index(){
    $autoEcole= AutoEcole::get();
  return response()->json($autoEcole, 200);
  }

    public function store(Request $request)
    {
      $user = User::find($request->user_id);
      if($user->role === "admin"){
        $autoEcole= AutoEcole::create([
          'nom'=> $request->nom,
          'adresse'=> $request->adresse,
          'user_id' => $request->user_id
        ]);
        $user->auto_ecole_id = $autoEcole->id; // Assign auto_ecole_id to the new user
        $user->save();
        return response()->json($autoEcole, 200);
      }else{
        return response()->json("user hasn't role Admin ", 400);
      }
      return response()->json("auto ecole not created ", 400);
     }



     public function show($id){


    
     }
      public function update(Request $request,$id){
    
        }
      public function showAutoEcoleByUserId($id){
        $user = User::find($id);
        return response()->json([
          'autoEcole'=>$user->autoEcole,
          'admin'=> $user
        ], 200);
      } 
public function delete($id) {
  
}
}
