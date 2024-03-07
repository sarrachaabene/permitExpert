<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeInscription;


class DemandeInscriptionController extends Controller
{
  public function index()
  {
    $demandeInscription=DemandeInscription::get();
    return response()->json($demandeInscription,200);
  }

  public function store(Request $request)
  {
    $demandeInscription= DemandeInscription::create($request->all());
    if($demandeInscription)
    { 
      $demandeInscription->save();
      return response()->json($demandeInscription, 200);
    }
    return response()->json("demandeInscription not created", 400);
   }



       
       public function Refuser($id) {
      /*  $seance = Seance::find($id);
              if (!$seance) {
            $msg = "seance not found";
            return response()->json($msg, 404);
        }
    
        if ($seance->delete()) {
            return response()->json("seance deleted successfully", 200);
        } else {
            return response()->json("seance to delete permis", 500);*/
            //refuser fait rien juste les donneé reste 
        }

public function Accepter($id) {
  $demandeInscription = Seance::find($id);
        if (!$demandeInscription) {
      $msg = "demandeInscription not found";
      return response()->json($msg, 404);
  }

  if ($demandeInscription->delete()) {
      return response()->json("demandeInscription accepted successfully", 200);
  } else {
      return response()->json("tu ne peut pas  accepter demandeInscription", 500);
  }
}
}
