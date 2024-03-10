<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\User;

class SeanceController extends Controller
{

public function store(Request $request)
  {
      // Trouver l'utilisateur par son ID
      $user = User::find($request->user_id);
      // Vérifier si l'utilisateur est un admin
      if ($user && $user->role === "candidat") {
          // Créer une nouvelle auto-école avec les données fournies dans la requête
          $seance = Seance::create([
              'type' => $request->type,
              'heureD' => $request->heureD,
              'heureF'=> $request->heureF,
              'dateS'=> $request->dateS,
                ]);
          // Assigner l'ID de l'auto-école à l'utilisateur
          $user->seance_id = $seance->id;
          $user->save();
          // Retourner la nouvelle auto-école en réponse
          return response()->json($seance, 200);
      } else {
          // Retourner une réponse indiquant que l'utilisateur n'est pas autorisé à créer une auto-école
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