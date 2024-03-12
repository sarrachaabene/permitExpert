<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\User;
use App\Models\Vehicule;


class SeanceController extends Controller
{

public function store(Request $request)
  {
      // Trouver l'utilisateur par son ID
      $user = User::find($request->user_id);
      $user1 = User::find($request->user_id1);
      $vehicule=Vehicule::find($request->vehicule_id);
      if (($user && $user->role === "candidat")&&($user1 && $user1->role === "moniteur")) {
          $seance = Seance::create([
              'type' => $request->type,
              'heureD' => $request->heureD,
              'heureF'=> $request->heureF,
              'dateS'=> $request->dateS,
                ]);
          $user->seance_id = $seance->id;
          $user->save();
          $user1->seance_id = $seance->id;
          $user1->save();
          $vehicule->seance_id = $seance->id;
          $vehicule->save();
          return response()->json($seance, 200);
      } else {
          return response()->json("User is not an candidat", 400);
      }
  }

     public function show($id){
      $seance = Seance::find($id);
      if($seance){
    return response()->json($seance, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
      public function update(Request $request,$id){
        $seance= Seance::find($id);
         if($seance){
           $seance->update($request->all());
           return response()->json($seance, 200);
 
         }else {
           $msg = "User not found";
           return response()->json($msg, 404);
       }
         }

         
         public function delete($id) {
          $seance = Seance::find($id);
                if (!$seance) {
              $msg = "seance not found";
              return response()->json($msg, 404);
          }
      
          if ($seance->delete()) {
              return response()->json("seance deleted successfully", 200);
          } else {
              return response()->json("seance to delete permis", 500);
          }
}
}