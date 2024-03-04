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
      $examen= Examen::create($request->all()); 
      if($examen)
      {
      
        return response()->json($examen, 200);
      }
      return response()->json("exam not created", 400);
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
