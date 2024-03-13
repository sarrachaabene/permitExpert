<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;



class ApiController extends Controller {
  public function index(){
    $user= User::get();
  return response()->json($user, 200);
  }

    public function store(Request $request)
    {
      $user= User::create($request->all());
      $autoecole = AutoEcole::find($request->auto_ecole_id);
      return($user) ;
      if($user->role='admin')

      {  $user->auto_ecole_id = $autoecole->id; // Assign auto_ecole_id to the new user
        $user->save();

        return response()->json($user, 200);
      }
      return response()->json("user not created", 400);
     }



     public function show($id){
      $user = User::find($id);
      if($user){
    return response()->json($user, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
      public function update(Request $request,$id){
       $user= User::find($id);
        if($user){
          $user->update($request->all());
          return response()->json($user, 200);

        }else {
          $msg = "User not found";
          return response()->json($msg, 404);
      }
        }



public function delete($id) {
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        $msg = "User not found";
        return response()->json($msg, 404);
    }

    // Delete the user
    $user->delete();

    // Check if the deletion was successful
    if ($user->trashed()) {
        return response()->json("User deleted successfully", 200);
    } else {
        return response()->json("Failed to delete user", 500);
    }
}

}
