<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resultat;
use App\Models\Examen;


class ResultatController extends Controller
{
  public function index(){
    $resultat= Resultat::get();
  return response()->json($resultat, 200);
  } 
     public function store(Request $request)
     { 
        $examen=Examen::find($request->examen_id);
         if ($examen){
           $resultat = Resultat::create([
             'type_resultat' => $request->type_resultat,
          
           ]);
           $examen->resultat_id = $resultat->id;
           $examen->save();
          
           return response()->json($resultat, 200);
         }else {
           return response()->json("examen not created", 400);
       }
     }

     public function show($id){
      $resultat = Resultat::find($id);
      if($resultat){
    return response()->json($resultat, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
      public function update(Request $request,$id){
       $resultat= Resultat::find($id);
        if($resultat){
          $resultat->update($request->all());
          return response()->json($resultat, 200);

        }else {
          $msg = "vehicule not found";
          return response()->json($msg, 404);
      }
        }



public function delete($id) {
    // Find the user by ID
    $resultat = Resultat::find($id);

    // Check if the user exists
    if (!$resultat) {
        $msg = "Result not found";
        return response()->json($msg, 404);
    }

    // Delete the user
    $resultat->delete();

    // Check if the deletion was successful
    if ($resultat->trashed()) {
        return response()->json("result deleted successfully", 200);
    } else {
        return response()->json("Failed to delete result", 500);
    }
}
}
