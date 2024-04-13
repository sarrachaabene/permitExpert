<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;
use Illuminate\Support\Facades\DB;
/**
 * @OA\Schema(
 *     schema="AutoEcole",
 *     title="AutoEcole",
 *     description="Modèle de l'auto-école",
 *     @OA\Property(
 *         property="nom",
 *         type="string",
 *         description="Nom de l'auto-école"
 *     ),
 *     @OA\Property(
 *         property="adresse",
 *         type="string",
 *         description="Adresse de l'auto-école"
 *     ),
 *     @OA\Property(
 *         property="autoecole_image",
 *         type="string",
 *         description="Image de l'auto-école"
 *     ),
 *     required={"nom", "adresse"}
 * )
 */
class AutoEcoleController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/autoEcole/index",
 *      operationId="getAllAutoEcoles",
 *      tags={"AutoEcole"},
 *      summary="Obtient toutes les auto-écoles",
 *      description="Renvoie toutes les auto-écoles disponibles",
 *      @OA\Response(
 *          response=200,
 *          description="Liste des auto-écoles",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/AutoEcole")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Erreur interne du serveur"
 *      )
 * )
 */
   public function index(){
    $autoEcole= AutoEcole::get();
  return response()->json($autoEcole, 200);
  } 

    /**
 * @OA\Get(
 *      path="/api/autoEcole/user",
 *      operationId="getUserAutoEcole",
 *      tags={"AutoEcole"},
 *      summary="Obtient toutes les auto-écoles",
 *      description="Renvoie les auto ecole pour chaque utilisateur",
 *      @OA\Response(
 *          response=200,
 *          description="Liste des auto-écoles",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/AutoEcole")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Erreur interne du serveur"
 *      )
 * )
 */
  public function getUsersAutoEcole()
  {
    $usersWithAutoEcole = User::has('autoEcole')->with('autoEcole')
    ->with('autoEcole') // Eager load the autoEcole relationship
    ->get();
    return response()->json($usersWithAutoEcole, 200);

  }

  /**
 * @OA\Post(
 *      path="/api/autoEcole/store",
 *      operationId="createAutoEcole",
 *      tags={"AutoEcole"},
 *      summary="Crée une nouvelle auto-école",
 *      description="Crée une nouvelle auto-école avec les données fournies dans la requête",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Données de l'auto-école à créer",
 *          @OA\JsonContent(ref="#/components/schemas/AutoEcole")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Nouvelle auto-école créée avec succès",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/AutoEcole"
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="L'utilisateur n'est pas un administrateur ou les données fournies sont invalides"
 *      )
 * )
 */

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
  

/**
 * @OA\Get(
 *      path="/api/autoEcole/show/{id}",
 *      operationId="getAutoEcoleById",
 *      tags={"AutoEcole"},
 *      summary="Récupère les détails d'une auto-école spécifique",
 *      description="Récupère les détails d'une auto-école en fonction de son ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de l'auto-école à récupérer",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Détails de l'auto-école récupérés avec succès",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/AutoEcole"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="ID non trouvé, aucune auto-école correspondante",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"message": "Votre ID n'est pas trouvé"}
 *          )
 *      )
 * )
 */

     public function show($id){
      $autoEcole = AutoEcole::find($id);
      if($autoEcole){
    return response()->json($autoEcole, 200);
  
      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);
        } 
} 
/**
 * @OA\Put(
 *      path="/api/autoEcole/update/{id}",
 *      operationId="updateAutoEcole",
 *      tags={"AutoEcole"},
 *      summary="Mise à jour des détails d'une auto-école",
 *      description="Mise à jour des détails d'une auto-école existante en fonction de son ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de l'auto-école à mettre à jour",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Objet de mise à jour de l'auto-école",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/AutoEcole"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Détails de l'auto-école mis à jour avec succès",
 *          @OA\JsonContent(
 *              type="object",
 *              ref="#/components/schemas/AutoEcole"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="ID non trouvé, aucune auto-école correspondante",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"message": "Auto-école non trouvée"}
 *          )
 *      )
 * )
 */

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

    /**
 * @OA\Get(
 *      path="/api/autoEcole/findAutoEcoleByUserId/{id}",
 *      operationId="showAutoEcoleByUserId",
 *      tags={"AutoEcole"},
 *      summary="Affiche les détails de l'auto-école associée à un utilisateur",
 *      description="Affiche les détails de l'auto-école associée à un utilisateur en fonction de son ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de l'utilisateur",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Détails de l'auto-école associée à l'utilisateur",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="autoEcole",
 *                  ref="#/components/schemas/AutoEcole",
 *                  description="Détails de l'auto-école associée"
 *              ),
 *              @OA\Property(
 *                  property="admin",
 *                  ref="#/components/schemas/User",
 *                  description="Détails de l'utilisateur (admin)"
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Utilisateur non trouvé, aucune auto-école associée",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"message": "User has no associated autoEcole"}
 *          )
 *      )
 * )
 */

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

  /**
 * @OA\Delete(
 *      path="/api/autoEcole/delete/{id}",
 *      operationId="deleteAutoEcole",
 *      tags={"AutoEcole"},
 *      summary="Supprime une auto-école et l'utilisateur associé",
 *      description="Supprime une auto-école et l'utilisateur associé en fonction de l'ID de l'utilisateur",
 *      @OA\Parameter(
 *          name="userId",
 *          in="path",
 *          description="ID de l'utilisateur",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Auto-école et utilisateur associé supprimés avec succès",
 *          @OA\JsonContent(
 *              type="string",
 *              example="autoEcole and associated user deleted successfully"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Auto-école non trouvée, aucun utilisateur associé",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"message": "autoEcole not found"}
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Impossible de supprimer l'auto-école ou l'utilisateur associé",
 *          @OA\JsonContent(
 *              type="object",
 *              example={"message": "Failed to delete associated user"}
 *          )
 *      )
 * )
 */

/*public function delete($userId)
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
}*/

public function delete($autoEcoleId)
{
    // Trouver l'auto-école par son ID
    $autoEcole = AutoEcole::find($autoEcoleId);

    if (!$autoEcole) {
        return response()->json("Auto-école not found", 404);
    }

    // Trouver l'utilisateur associé à cette auto-école
    $user = User::where('auto_ecole_id', $autoEcoleId)->first();

    if (!$user) {
        return response()->json("Associated user not found", 404);
    }

    try {
        // Commencer une transaction
        DB::beginTransaction();

        // Supprimer l'auto-école
        $autoEcole->delete();

        // Supprimer l'utilisateur associé
        $user->delete();

        // Valider la transaction
        DB::commit();

        return response()->json("Auto-école and associated user deleted successfully", 200);
    } catch (\Exception $e) {
        // En cas d'erreur, annuler la transaction et renvoyer une réponse d'erreur
        DB::rollBack();
        return response()->json("Failed to delete auto-école and associated user", 500);
    }
}




  
  
}
