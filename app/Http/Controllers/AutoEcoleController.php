<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;

class AutoEcoleController extends Controller
{
  public function index(){
    $autoEcole= AutoEcole::get();
  return response()->json($autoEcole, 200);
  }

  public function store(Request $request)
  {
      // Trouver l'utilisateur par son ID
      $user = User::find($request->user_id);
      
      // Vérifier si l'utilisateur est un admin
      if ($user && $user->role === "admin") {
          // Créer une nouvelle auto-école avec les données fournies dans la requête
          $autoEcole = AutoEcole::create([
              'nom' => $request->nom,
              'adresse' => $request->adresse,
              'description'=> $request->description
            ]);
  
          // Assigner l'ID de l'auto-école à l'utilisateur
          $user->auto_ecole_id = $autoEcole->id;
          $user->save();
  
          // Retourner la nouvelle auto-école en réponse
          return response()->json($autoEcole, 200);
      } else {
          // Retourner une réponse indiquant que l'utilisateur n'est pas autorisé à créer une auto-école
          return response()->json("User is not an admin", 400);
      }
  }
  


     public function show($id){
      $autoEcole = AutoEcole::find($id);
      if($autoEcole){
    return response()->json($autoEcole, 200);
  
      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);
  
  
  
      } 

    
     } 
    public function update(Request $request,$id)
    {
      $autoEcole= AutoEcole::find($id);
      if($autoEcole){
        $autoEcole->update($request->all());
        return response()->json($autoEcole, 200);

      }else {
        $msg = "User not found";
        return response()->json($msg, 404);
      }
    }
    public function showAutoEcoleByUserId($id)
    {
      $user = User::find($id);
      if (!$user) {
          $msg = "User not found";
          return response()->json($msg, 404);
      }
  
      // Vérifier si l'utilisateur a une auto-école associée
      if ($user->autoEcole) {
          return response()->json([
              'autoEcole' => $user->autoEcole,
              'admin' => $user
          ], 200);
      } else {
          // Si l'utilisateur n'a pas d'auto-école associée
          return response()->json("User has no associated autoEcole", 404);
      }
  }

public function delete($userId)
{
    $user = User::find($userId);
    $autoEcole = $user->autoEcole;

    if (!$autoEcole) {
        $msg = "autoEcole not found";
        return response()->json($msg, 404);
    }

    // Supprimer l'auto-école elle-même
    if ($autoEcole->delete()) {
        // Supprimer l'utilisateur associé à cette auto-école
        if ($user->delete()) {
            return response()->json("autoEcole and associated user deleted successfully", 200);
        } else {
            return response()->json("Failed to delete associated user", 500);
        }
    } else {
        return response()->json("impossible to delete autoEcole", 500);
    }
}

  
  
}
