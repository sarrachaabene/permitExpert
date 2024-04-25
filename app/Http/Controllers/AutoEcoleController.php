<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
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
 *      path="/api/autoEcole",
 *      operationId="getAutoEcoles",
 *      tags={"AutoEcole"},
 *      summary="Récupère la liste de toutes les auto-écoles",
 *      description="Retourne une liste de toutes les auto-écoles disponibles",
 *      @OA\Response(
 *          response=200,
 *          description="Liste des auto-écoles récupérée avec succès",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/AutoEcole")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Aucune auto-école n'a été trouvée"
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Erreur interne du serveur"
 *      )
 * )
 */


    // TODO: error handling
    public function index()
    {
      try {
          $autoEcole = AutoEcole::get();
          if ($autoEcole->isEmpty()) {
              return response()->json("Aucune auto-école n'a été trouvée", 404);
          }
          return response()->json($autoEcole, 200);
      } catch (\Exception $e) {
          return response()->json("Erreur interne du serveur", 500);
      }
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
    // TODO: error handling

    public function getUsersAutoEcole()
    {
        try {
            $usersWithAutoEcole = User::has('autoEcole')->get();
            
            if ($usersWithAutoEcole->isEmpty()) {
                return response()->json("Aucun utilisateur avec une auto-école n'a été trouvé", 404);
            }
            
            return response()->json($usersWithAutoEcole, 200);
        } catch (\Exception $e) {
            return response()->json("Erreur interne du serveur", 500);
        }
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
    // TODO: Add validation and error handling test sur les role comme indiquer dans mon message
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
            $user = User::find($request->user_id);
                if ($user && $user->role === "admin") {
            $autoEcole = AutoEcole::create([
                'nom' => $request->nom,
                'adresse' => $request->adresse,
                'description' => $request->description
            ]);
                $user->auto_ecole_id = $autoEcole->id;
            $user->save();
            return response()->json($autoEcole, 201);
        } else {
            return response()->json("User is not admin", 403);
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

     // TODO:  changer la reponse 404

     public function show($id)
     {  $validator = Validator::make(['id' => $id], [
    'id' => 'required|integer|exists:auto_ecoles,id',
]);
if ($validator->fails()) {
    return response()->json($validator->errors(), 400);
}
$autoEcole = AutoEcole::find($id);
if ($autoEcole) {
    return response()->json($autoEcole, 200);
} else {
    $msg = "L'auto-école avec l'ID spécifié n'a pas été trouvée.";
    return response()->json($msg, 404);
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
      //TODO Validate the data  dont update without verifying the request as this will cause data to be removed
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
    // TODO: Add validation and error handling
public function showAutoEcoleByUserId($id)
    {
    $validator = Validator::make(['id' => $id], [
      'id' => 'required|integer|exists:users,id',
  ]);
  if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
  }
  $user = User::find($id);
  if (!$user) {
      return response()->json("User not found", 404);
  }
  if ($user->autoEcole) {
      return response()->json([
          'autoEcole' => $user->autoEcole,
          'admin' => $user
      ], 200);
  } else {
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

public function delete($userId)
{
  // Trouver l'utilisateur par son ID
  $user = User::find($userId);

  // Vérifier si l'utilisateur existe
  if (!$user) {
      $msg = "User not found";
      return response()->json($msg, 404);
  }

  // Récupérer l'auto-école associée à l'utilisateur
  $autoEcole = $user->autoEcole;

  // Vérifier si l'auto-école existe
  if (!$autoEcole) {
      $msg = "Auto-école not found";
      return response()->json($msg, 404);
  }

  try {
      // Commencer une transaction
      DB::beginTransaction();

      // Supprimer tous les utilisateurs associés à cette auto-école
      $usersDeleted = $autoEcole->users()->delete();

      // Supprimer l'auto-école elle-même
      $autoEcoleDeleted = $autoEcole->delete();

      // Valider la transaction
      DB::commit();

      // Vérifier si la suppression s'est bien déroulée
      if ($autoEcoleDeleted) {
          return response()->json("Auto-école and associated users deleted successfully", 200);
      } else {
          return response()->json("Failed to delete auto-école and associated users", 500);
      }
  } catch (\Exception $e) {
      // En cas d'erreur, annuler la transaction et renvoyer une réponse d'erreur
      DB::rollBack();
      return response()->json("Failed to delete auto-école and associated users: " . $e->getMessage(), 500);
  }
}

//Tres bien
/* public function delete($autoEcoleId)
{
  try {
    // Commencer une transaction
    DB::beginTransaction();

    // Trouver l'auto-école par son ID
    $autoEcole = AutoEcole::find($autoEcoleId);

    // Vérifier si l'auto-école existe
    if (!$autoEcole) {
        return response()->json("Auto-école not found", 404);
    }

    // Supprimer tous les utilisateurs associés à cette auto-école
    $users = $autoEcole->user; // Obtenir tous les utilisateurs associés
    foreach ($users as $user) {
        $user->delete(); // Supprimer chaque utilisateur associé
    }

    // Supprimer l'auto-école
    $autoEcole->delete();

    // Valider la transaction
    DB::commit();

    return response()->json("Auto-école and associated users deleted successfully", 200);
} catch (\Exception $e) {
    // En cas d'erreur, annuler la transaction et renvoyer une réponse d'erreur
    DB::rollBack();
    return response()->json("Failed to delete auto-école and associated users: " . $e->getMessage(), 500);
}
}
 */



  
  
}
