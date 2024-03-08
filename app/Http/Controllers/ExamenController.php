<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;

class ExamenController extends Controller
{
  public function index(){
    $examen= Examen::get();
  return response()->json($examen, 200);
  }

  public function store(Request $request)
  {
      // Vérifier si l'utilisateur est authentifié
      if ($request->user() && $request->user()->can('create Examen')) {
          // L'utilisateur est authentifié et a la permission pour créer un examen
  
          // Créer l'examen
          $examen = Examen::create($request->all());
  
          if ($examen) {
              return response()->json($examen, 200);
          } else {
              return response()->json("Exam not created", 400);
          }
      } else {
          // L'utilisateur n'est pas authentifié ou n'a pas la permission pour créer un examen
          return response()->json("Unauthorized", 403);
      }
  }
  

     public function show($id){
      $examen = Examen::find($id);
      if($examen){
    return response()->json($examen, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
      public function update(Request $request,$id){
       $examen= Examen::find($id);
        if($examen){
          $examen->update($request->all());
          return response()->json($examen, 200);

        }else {
          $msg = "User not found";
          return response()->json($msg, 404);
      }
        }



public function delete($id) {
    // Find the user by ID
    $examen = Examen::find($id);

    // Check if the user exists
    if (!$examen) {
        $msg = "Exam not found";
        return response()->json($msg, 404);
    }

    // Delete the user
    $examen->delete();

    // Check if the deletion was successful
    if ($examen->trashed()) {
        return response()->json("Exam deleted successfully", 200);
    } else {
        return response()->json("Failed to delete Exam", 500);
    }
}

}
