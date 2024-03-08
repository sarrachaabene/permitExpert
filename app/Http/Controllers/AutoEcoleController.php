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
              'user_id' => $request->user_id // Assigner l'ID de l'utilisateur à l'auto-école
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
  public function show($id){}
      public function update(Request $request,$id){
    
        }
      public function showAutoEcoleByUserId($id){
        $user = User::find($id);
        return response()->json([
          'autoEcole'=>$user->autoEcole,
          'admin'=> $user
        ], 200);
      }
public function delete($id) {
  
}
}
