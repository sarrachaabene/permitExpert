<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\User;
use App\Models\Vehicule;


class ExamenController extends Controller
{
  public function index(){
    $examen= Examen::get();
  return response()->json($examen, 200);
  }

  public function store(Request $request)
  {
      $user = User::find($request->user_id);
     $vehicule=Vehicule::find($request->vehicule_id);
      if ($user && $user->role=='candidat'&& $vehicule ){
        $examen = Examen::create([
          'type' => $request->type,
          'heureD' => $request->heureD,
          'heureF'=> $request->heureF,
          'dateE'=> $request->dateE,
          'user_id'=> $request->user_id,
          'vehicule_id'=> $request->vehicule_id,
        ]);
        return response()->json($examen, 200);
      }else {
        return response()->json("examen not created", 400);
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
      public function ShowExamensBycandidatId($candidatId)
      {
          $examen = Examen::where('user_id', $candidatId)->get();
          return response()->json($examen, 200);
      }
      public function ShowExamensByvehiculeId($vehiculeId)
      {
          $examen = Examen::where('vehicule_id', $vehiculeId)->get();
          return response()->json($examen, 200);
      }
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

public function AccepterExamen($id) {
  $examen = Examen::find($id);
  $examen->status = 'confirmee';
  $examen->save();
  return('examen accepter');

 }


 public function RefuserExamen($id) {
  $examen = Examen::find($id);
  $examen->status = 'refusee';
  $examen->save();
  return('examen refusee');

 }
}
