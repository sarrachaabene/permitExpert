<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Seance;
use App\Models\Examen;
use App\Models\Transaction;
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
            $autoEcoles = AutoEcole::withTrashed()->get();
            
            if ($autoEcoles->isEmpty()) {
                return response()->json("Aucune auto-école n'a été trouvée", 404);
            }
            
            $autoecoleDetails = [];
            foreach ($autoEcoles as $autoEcole) {
               $admin = User::withTrashed()
                            ->where('role', 'admin')
                            ->where('auto_ecole_id', $autoEcole->id)
                            ->first();
                
                $autoecoleDetails[] = [
                    "id" => $autoEcole->id,
                    "nom" => $autoEcole->nom,
                    "adresse" => $autoEcole->adresse,
                    "description" => $autoEcole->description,
                    "deleted_at" => $autoEcole->deleted_at, 
                    "admin" => $admin ? [
                        "id" => $admin->id,
                        "user_name" => $admin->user_name,
                        "email" => $admin->email,
                        "numTel" => $admin->numTel
                    ] : [ 
                        "id" => null,
                        "user_name" => null,
                        "email" => null,
                        "numTel" => null
                    ]
                ];
            }
            
            return response()->json($autoecoleDetails, 200);
        } catch (\Exception $e) {
            return response()->json("Erreur interne du serveur", 500);
        }
    }
    
    
    

    


  public function countAutoEcoles()
  {
      try {
          $count = AutoEcole::count(); 
          if ($count === 0) {
              return response()->json("Aucune auto-école n'a été trouvée", 404);
          }
          return response()->json([
              'count' => $count 
          ], 200);
      } catch (\Exception $e) {
          return response()->json("Erreur interne du serveur", 500);
      }
  }
  public function countAutoEcolesByMonth()
  {
      try {
          // Créer un tableau de tous les mois de l'année
          $months = range(1, 12);
  
          // Sélectionner le nombre d'auto-écoles pour chaque mois, même s'il est nul
          $counts = AutoEcole::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                              ->orWhereNull('deleted_at') // Pour inclure les enregistrements non supprimés
                              ->groupBy(DB::raw('MONTH(created_at)'))
                              ->orderBy(DB::raw('MONTH(created_at)'))
                              ->get();
  
          // Remplir les mois manquants avec un compteur de 0
          $result = [];
          foreach ($months as $month) {
              $count = $counts->where('month', $month)->first();
              $result[] = [
                  'month' => $month,
                  'count' => $count ? $count->count : 0,
              ];
          }
  
          return response()->json($result, 200);
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
            $usersWithAutoEcole = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->has('autoEcole')->with('autoEcole')->get();
            
            if ($usersWithAutoEcole->isEmpty()) {
                return response()->json("Aucun utilisateur admin avec une auto-école n'a été trouvé", 404);
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
    try {
        $request->validate([
            'user_name' => 'required|exists:users,user_name',
            'nom' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $existingAutoEcole = AutoEcole::where('nom', $value)->first();
                    if ($existingAutoEcole) {
                        $fail('Le nom de l\'auto-école existe déjà.');
                    }
                },
            ],
            'adresse' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $user = User::where('user_name', $request->user_name)->first();

        if (!$user) {
            return response()->json(["error" => "L'utilisateur avec le nom d'utilisateur spécifié n'existe pas."], 404);
        }

        if ($user->role !== "admin") {
            return response()->json(["error" => "L'utilisateur n'est pas un administrateur."], 403);
        }

        if ($user->auto_ecole_id) {
            return response()->json(["error" => "L'administrateur a déjà une auto-école."], 400);
        }

        $autoEcole = AutoEcole::create([
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'description' => $request->description
        ]);

        $user->auto_ecole_id = $autoEcole->id;
        $user->save();

        return response()->json($autoEcole, 201);
    } catch (\Exception $e) {
        return response()->json(["error" => "Erreur lors de la création de l'auto-école : " . $e->getMessage()], 500);
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
      public function update(Request $request, $id)
      {
          try {
              $request->validate([
                  'user_name' => 'required|exists:users,user_name',
                  'nom' => [
                      'sometimes',
                      'string',
                      'max:255',
                      function ($attribute, $value, $fail) use ($id) {
                          $existingAutoEcole = AutoEcole::where('nom', $value)->where('id', '!=', $id)->first();
                          if ($existingAutoEcole) {
                              $fail('Le nom de l\'auto-école existe déjà.');
                          }
                      },
                  ],
                  'adresse' => 'sometimes|string|max:255',
                  'description' => 'sometimes|string|max:255',
              ]);
      
              $user = User::where('user_name', $request->user_name)->first();
              if (!$user) {
                  return response()->json(["error" => "L'utilisateur avec le nom d'utilisateur spécifié n'existe pas."], 404);
              }
      
              if ($user->role !== "admin") {
                  return response()->json(["error" => "L'utilisateur n'est pas un administrateur."], 403);
              }
      
              $autoEcole = AutoEcole::find($id);
              if (!$autoEcole) {
                  return response()->json(["error" => "L'auto-école avec l'ID spécifié n'existe pas."], 404);
              }
      
              if ($user->auto_ecole_id && $user->auto_ecole_id != $id) {
                  return response()->json(["error" => "L'administrateur a déjà une auto-école."], 400);
              }
      
              $autoEcole->update($request->all());
          $user->auto_ecole_id = $autoEcole->id;
        $user->save();
              return response()->json($autoEcole, 200);
          } catch (\Exception $e) {
              return response()->json(["error" => "Erreur lors de la mise à jour de l'auto-école : " . $e->getMessage()], 500);
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


 /* public function delete($id)
 {
     try {
         // Démarrer une transaction
         DB::beginTransaction();
 
         // Recherche de l'auto-école à supprimer
         $autoEcole = AutoEcole::find($id);
 
         // Vérifier si l'auto-école existe
         if (!$autoEcole) {
             return response()->json(["error" => "L'auto-école avec l'ID spécifié n'existe pas."], 404);
         }
 
         // Récupérer l'ID de l'administrateur associé à l'auto-école
         $adminId = $autoEcole->user_id;
 
         // Supprimer les références à l'auto-école dans la table users
         User::where('auto_ecole_id', $id)->update(['auto_ecole_id' => null]);
 
         // Supprimer l'auto-école
         $autoEcole->delete();
 
         // Vérifier si l'administrateur associé a d'autres auto-écoles
         $otherAutoEcoles = AutoEcole::where('user_id', $adminId)->count();
 
         // Si l'administrateur n'a pas d'autres auto-écoles, le supprimer également
         if ($otherAutoEcoles == 0) {
             User::find($adminId)->delete();
         }
 
         // Valider la transaction
         DB::commit();
 
         return response()->json(["message" => "L'auto-école a été supprimée avec succès."], 200);
     } catch (\Exception $e) {
         // Annuler la transaction en cas d'erreur
         DB::rollback();
         return response()->json(["error" => "Erreur lors de la suppression de l'auto-école : " . $e->getMessage()], 500);
     }
 } */
 

//Tres bien
public function delete($autoEcoleId)
{
    try {
        DB::beginTransaction();

        Vehicule::where('auto_ecole_id', $autoEcoleId)->delete();

        Seance::where('auto_ecole_id', $autoEcoleId)->delete();

        Examen::where('auto_ecole_id', $autoEcoleId)->delete();
        Transaction::where('auto_ecole_id', $autoEcoleId)->delete();


        User::where('auto_ecole_id', $autoEcoleId)->update(['deleted_at' => now()]);

        AutoEcole::where('id', $autoEcoleId)->update(['deleted_at' => now()]);

        DB::commit();
        return response()->json("Auto-école, associated vehicles, sessions, exams, and users deleted successfully", 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json("Failed to delete auto-école, associated vehicles, sessions, exams, and users: " . $e->getMessage(), 500);
    }}





  
  
}
