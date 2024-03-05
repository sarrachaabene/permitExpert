<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;

class PaiementController extends Controller
{
  public function index()
  {
    $paiement=Paiement::get();
    return response()->json($paiement,200);
  }




  public function store(Request $request)
  {
      // Créer un nouvel objet Paiement avec les données de la requête
      $paiement = Paiement::create($request->all());
      
      // Retourner l'objet Paiement créé avec le code de statut 200
      return response()->json($paiement, 200);
  }
  

   public function show($id){
    $paiement = Paiement::find($id);
    if($paiement){
  return response()->json($paiement, 200);

    }else{
      $msg="votre id n'est pas trouve";
          return response()->json($msg, 200);



    }   }


    public function update(Request $request,$id){
      $paiement= Paiement::find($id);
       if($paiement){
         $paiement->update($request->all());
         return response()->json($paiement, 200);

       }else {
         $msg = "paiement not found";
         return response()->json($msg, 404);
     }
       }

       

       
      
}
