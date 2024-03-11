<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;



class VehiculeController extends Controller
{
  public function index(){
    $vehicule= Vehicule::get();
  return response()->json($vehicule, 200);
  }

    public function store(Request $request)
    {
      
      $vehicule= Vehicule::create($request->all()); 
      if($vehicule)
      {
        return response()->json($vehicule, 200);
      }
      return response()->json("vehicule not created", 400);
     }



     public function show($id){
      $vehicule = Vehicule::find($id);
      if($vehicule){
    return response()->json($vehicule, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
      public function update(Request $request,$id){
       $vehicule= Vehicule::find($id);
        if($vehicule){
          $vehicule->update($request->all());
          return response()->json($vehicule, 200);

        }else {
          $msg = "vehicule not found";
          return response()->json($msg, 404);
      }
        }



public function delete($id) {
    // Find the user by ID
    $vehicule = Vehicule::find($id);

    // Check if the user exists
    if (!$vehicule) {
        $msg = "Vehicule not found";
        return response()->json($msg, 404);
    }

    // Delete the user
    $vehicule->delete();

    // Check if the deletion was successful
    if ($vehicule->trashed()) {
        return response()->json("vehicule deleted successfully", 200);
    } else {
        return response()->json("Failed to delete vehicule", 500);
    }
}



public function deletee($id) {
  // Find the user by ID
  $vehicule = Vehicule::find($id);

  // Check if the user exists
  if (!$vehicule) {
      $msg = "Vehicule not found";
      return response()->json($msg, 404);
  }

  // Delete the user
  $vehicule->delete();

  // Check if the deletion was successful
  if ($vehicule->trashed()) {
      return response()->json("vehicule deleted successfully", 200);
  } else {
      return response()->json("Failed to delete vehicule", 500);
  }
}



}
