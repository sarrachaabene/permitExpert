<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Auth;
use App\Models\Examen;

/**
 * @OA\Schema(
 *     schema="Seance",
 *     title="Seance",
 *     description="Seance model",
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="heureD",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="heureF",
 *         type="string",
 *         format="date-time"
 *     ),
 *     @OA\Property(
 *         property="dateS",
 *         type="string",
 *         format="date"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="vehicule_id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="candidat_id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="moniteur_id",
 *         type="integer",
 *         format="int64"
 *     ),
 *     @OA\Property(
 *         property="candidat_status",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="moniteur_status",
 *         type="string"
 *     ),
 * )
 */
class SeanceController extends Controller
{
  /**
   * @OA\Get(
   *      path="/api/seance/index",
   *      operationId="getSeancesList",
   *      tags={"Seances"},
   *      summary="Get list of Seances",
   *      description="Returns list of Seances",
   *      @OA\Response(
   *          response=200,
   *          description="Successful operation",
   *          @OA\JsonContent(
   *              type="array",
   *              @OA\Items(ref="#/components/schemas/Seance")
   *          ),
   *      ),
   * )
   */
      // TODO: Add error handling

      public function index()
      {
          $adminId = Auth::id(); 
          $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
          
          try {
              $seances = Seance::where('auto_ecole_id', $adminAutoEcoleId)->get();
              $examens = Examen::where('auto_ecole_id', $adminAutoEcoleId)->get();
      
              $examenDetails = [];
              foreach ($examens as $examen) {
                  $candidatDetails = User::find($examen->user_id);
                  $vehiculeDetails = Vehicule::find($examen->vehicule_id);
                  $examenDetails[] = [
                      "id" => $examen->id,
                      "type" => $examen->type,
                      "status" => $examen->status,
                      "heureD" => $examen->heureD,
                      "heureF" => $examen->heureF,
                      "dateE" => $examen->dateE,
                      "user_name" => $candidatDetails ? $candidatDetails->user_name : "",
                      "immatricule" => $vehiculeDetails ? $vehiculeDetails->immatricule: "",
                  ];
              }
              $seanceDetails = [];
              foreach ($seances as $seance) {
                  $candidatDetails = User::find($seance->candidat_id);
                  $moniteurDetails = User::find($seance->moniteur_id);
                  $vehiculeDetails = Vehicule::find($seance->vehicule_id);
                  $seanceDetails[] = [
                      "id" => $seance->id,
                      "type" => $seance->type,
                      "heureD" => $seance->heureD,
                      "heureF" => $seance->heureF,
                      "dateS" => $seance->dateS,
                      "status" => $seance->status,
                      "user_name" => $candidatDetails ? $candidatDetails->user_name : "",
                      "Moniteur_name" => $moniteurDetails ? $moniteurDetails->user_name : "",
                      "immatricule" => $vehiculeDetails ? $vehiculeDetails->immatricule: "",
                  ];
              }
              return response()->json(["seances" => $seanceDetails, "examens" => $examenDetails], 200);
          } catch (\Exception $e) {
              $error = "Erreur lors de la récupération des séances et examens: " . $e->getMessage();
              return response()->json(["error" => $error], 500);
          }
      }
      
      
/**
 * @OA\Post(
 *      path="/api/seance/store",
 *      operationId="createSeance",
 *      tags={"Seances"},
 *      summary="Create a new Seance",
 *      description="Create a new Seance",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/Seance")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Seance")
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *      )
 * )
 */
    // TODO: Add validation and error handling verify they  are in the database and all in the same autoecoles
    public function store(Request $request)
    {
        $adminId = Auth::id(); 
        $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
    
        try {
            $validatedData = $request->validate([
                'type' => 'required|string|in:code,circuit,parc',
                'heureD' => 'required|date_format:H:i',
                'heureF' => 'required|date_format:H:i|after:heureD',
                'dateS' => 'required|date',
                'moniteur_id' => 'required|exists:users,id,role,moniteur,auto_ecole_id,'.$adminAutoEcoleId,
                'candidat_id' => 'required|exists:users,id,role,candidat,auto_ecole_id,'.$adminAutoEcoleId,
                'vehicule_id' => 'required|exists:vehicules,id,auto_ecole_id,'.$adminAutoEcoleId,
            ]);
                $existingSeance = Seance::where('moniteur_id', $validatedData['moniteur_id'])
                ->where('candidat_id', $validatedData['candidat_id'])
                ->where('vehicule_id', $validatedData['vehicule_id'])
                ->where('dateS', $validatedData['dateS'])
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('heureD', [$validatedData['heureD'], $validatedData['heureF']])
                        ->orWhereBetween('heureF', [$validatedData['heureD'], $validatedData['heureF']]);
                })
                ->exists();
    
            if ($existingSeance) {
                return response()->json(["error" => "Il existe déjà une séance planifiée pour ce moniteur, candidat et véhicule à ce moment."], 400);
            }
                $seance = Seance::create([
                'type' => $validatedData['type'],
                'heureD' => $validatedData['heureD'],
                'heureF' => $validatedData['heureF'],
                'dateS' => $validatedData['dateS'],
                'status'=>'en attente',
                'candidat_status'=>'en attente',
                'moniteur_status'=>'en attente',
                'moniteur_id' => $validatedData['moniteur_id'],
                'candidat_id' => $validatedData['candidat_id'],
                'vehicule_id' => $validatedData['vehicule_id'],
                'auto_ecole_id' => $adminAutoEcoleId,
            ]);
    
            return response()->json($seance, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la création de la séance: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
    
  /**
 * @OA\Get(
 *      path="/api/seance/show/{id}",
 *      operationId="getSeanceById",
 *      tags={"Seances"},
 *      summary="Get a Seance by ID",
 *      description="Returns a single Seance",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the seance to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Seance")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Seance not found"
 *      )
 * )
 */
    // TODO: 404 not found
    public function show($id)
    {
      
        try {
            $seance = Seance::find($id);
                if (!$seance) {
                $msg = "La séance avec l'ID spécifié n'a pas été trouvée.";
                return response()->json(["error" => $msg], 404);
            }
            return response()->json($seance, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération de la séance: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
/**
 * @OA\Get(
 *      path="/api/seance/ShowSeanceBycandidatId/{id}",
 *      operationId="getSeanceByCandidatId",
 *      tags={"Seances"},
 *      summary="Get Seances by Candidat ID",
 *      description="Returns Seances associated with a specific Candidat",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the candidat",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Seance")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="No seance found for the candidat"
 *      )
 * )
 */
    // TODO: Add error handling
    public function ShowSeanceBycandidatId($candidatId)
    {
        try {
            $seances = Seance::where('candidat_id', $candidatId)->get();
                if ($seances->isEmpty()) {
                return response()->json(["error" => "Aucune séance trouvée pour le candidat spécifié."], 404);
            }
            return response()->json($seances, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des séances: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }    

    //Affichage pour candidat

    public function ShowSeanceForCandidat($date) 
{
    try {
        if (!Auth::check()) {
            return response()->json(["error" => "Utilisateur non authentifié."], 401);
        }
        
        $candidatId = Auth::id();
        $seances = Seance::where('candidat_id', $candidatId)
            ->whereDate('dateS', $date)
            ->get();

        if ($seances->isEmpty()) {
            return response()->json(["error" => "Aucune séance trouvée pour le candidat spécifié à cette date."], 404);
        }
        
        return response()->json($seances, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des séances: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}



//index examen and seance by date
public function showSeancesAndExams($date)
{
    try {
        if (!Auth::check()) {
            return response()->json(["error" => "Utilisateur non authentifié."], 401);
        }
        $user = Auth::user(); 
        if ($user->role === 'candidat') {
            $seances = Seance::where('candidat_id', $user->id)
                ->whereDate('dateS', $date)
                ->get();
            $examens = Examen::where('user_id', $user->id)
                ->whereDate('dateE', $date)
                ->get();
        } elseif ($user->role === 'moniteur') {
            $seances = Seance::where('moniteur_id', $user->id)
                ->whereDate('dateS', $date)
                ->get();
            $examens = collect();
        } else {
            return response()->json(["error" => "L'utilisateur n'est pas autorisé à accéder à cette ressource."], 403);
        }

        if ($seances->isEmpty() && $examens->isEmpty()) {
            return response()->json(["error" => "Aucune séance ou examen trouvé pour l'utilisateur spécifié à cette date."], 404);
        }
        return response()->json(["seances" => $seances, "examens" => $examens], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des séances et des examens: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}   
      /**
 * @OA\Get(
 *      path="/api/seance/ShowSeanceByvehiculeId/{id}",
 *      operationId="ShowSeanceByvehiculeId",
 *      tags={"Seances"},
 *      summary="Get Seances by vehicule ID",
 *      description="Returns Seances associated with a specific vehicule",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the vehicule",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Seance")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="No seance found for the vehicule"
 *      )
 * )
 */
    // TODO: Add and error handling
    public function ShowSeanceByvehiculeId($vehiculeId)
    {
        try {
            $seances = Seance::where('vehicule_id', $vehiculeId)->get();
                if ($seances->isEmpty()) {
                return response()->json(["error" => "Aucune séance trouvée pour le véhicule spécifié."], 404);
            }
            return response()->json($seances, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des séances: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }  
            /**
 * @OA\Get(
 *      path="/api/seance/ShowSeanceBymoniteurId/{id}",
 *      operationId="ShowSeanceBymoniteurId",
 *      tags={"Seances"},
 *      summary="Get Seances by moniteur ID",
 *      description="Returns Seances associated with a specific moniteur",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the moniteur",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Seance")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="No seance found for the moniteur"
 *      )
 * )
 */
    // TODO: Add error handling 
    public function ShowSeanceBymoniteurId($moniteurId)
    {
        try {
            $seances = Seance::where('moniteur_id', $moniteurId)->get();
    
            if ($seances->isEmpty()) {
                return response()->json(["error" => "Aucune séance trouvée pour le moniteur spécifié."], 404);
            }
            return response()->json($seances, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des séances: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
//Affichage de tous les evennement
public function indexForMobile()
{
    $user = Auth::user(); 
    try {
        if ($user->role === 'candidat') {
            $seances = Seance::where('candidat_id', $user->id)->pluck('dateS');
            $examens = Examen::where('user_id', $user->id)->pluck('dateE');
        } elseif ($user->role === 'moniteur') {
            $seances = Seance::where('moniteur_id', $user->id)->pluck('dateS');
            $examens = collect(); // Créer une collection vide pour éviter les erreurs
        } else {
            return response()->json(["error" => "L'utilisateur n'est pas autorisé à accéder à cette ressource."], 403);
        }
        
        if ($seances->isEmpty() && $examens->isEmpty()) {
            return response()->json(["error" => "Aucune séance ou examen trouvé pour cet utilisateur."], 404);
        }

        return response()->json(["seances" => $seances, "examens" => $examens], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des séances et des examens: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}

//Affichage pour Moniteur
    public function ShowSeanceForMoniteur($date) 
    {
        try {
            if (!Auth::check()) {
                return response()->json(["error" => "Utilisateur non authentifié."], 401);
            }
            
            $moniteurId = Auth::id();
            $seances = Seance::where('moniteur_id', $moniteurId)
                ->whereDate('dateS', $date) 
                ->get();
    
            if ($seances->isEmpty()) {
                return response()->json(["error" => "Aucune séance trouvée pour le candidat spécifié à cette date."], 404);
            }
            
            return response()->json($seances, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des séances: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }



      /**
 * @OA\Put(
 *      path="/api/seance/update/{id}",
 *      operationId="updateSeance",
 *      tags={"Seances"},
 *      summary="Update an existing Seance",
 *      description="Update an existing Seance record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Seance to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/Seance")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Seance")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Seance not found"
 *      )
 * )
 */
    // TODO: Add validation and error handling and test if the authenticated user can update

    public function update(Request $request, $id)
    {
        try {
            $seance = Seance::find($id);
                if (!$seance) {
                return response()->json(["error" => "La séance avec l'ID spécifié n'a pas été trouvée."], 404);
            }
                $validatedData = $request->validate([
                'type' => 'string|in:code,circuit,parc',
                'heureD' => 'date_format:H:i',
                'heureF' => 'date_format:H:i|after:heureD',
                'dateS' => 'date',
                'moniteur_id' => 'exists:users,id,role,moniteur',
                'candidat_id' => 'exists:users,id,role,candidat',
                'vehicule_id' => 'exists:vehicules,id',
            ]);
                $existingSeance = Seance::where('moniteur_id', $validatedData['moniteur_id'])
                ->where('candidat_id', $validatedData['candidat_id'])
                ->where('vehicule_id', $validatedData['vehicule_id'])
                ->where('dateS', $validatedData['dateS'])
                ->where(function ($query) use ($validatedData) {
                    $query->whereBetween('heureD', [$validatedData['heureD'], $validatedData['heureF']])
                        ->orWhereBetween('heureF', [$validatedData['heureD'], $validatedData['heureF']]);
                })
                ->where('id', '!=', $id) 
                ->exists();
    
            if ($existingSeance) {
                return response()->json(["error" => "Il existe déjà une séance planifiée pour ce moniteur, candidat et véhicule à ce moment."], 400);
            }
                $seance->update($validatedData);
            return response()->json($seance, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la mise à jour de la séance: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
         /**
 * @OA\Delete(
 *      path="/api/seance/delete/{id}",
 *      operationId="deleteSeance",
 *      tags={"Seances"},
 *      summary="Delete an existing Seance",
 *      description="Delete an existing Seance record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Seance to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Seance deleted successfully",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Seance deleted successfully"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Seance not found"
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Failed to delete Seance"
 *      )
 * )
 */
public function delete($id)
{
    try {
        $seance = Seance::find($id);

        if (!$seance) {
            $msg = "La séance n'a pas été trouvée.";
            return response()->json(["error" => $msg], 404);
        }
        if ($seance->delete()) {
            return response()->json("La séance a été supprimée avec succès.", 200);
        } else {
            return response()->json("Erreur lors de la suppression de la séance.", 500);
        }
    } catch (\Exception $e) {
        $error = "Erreur lors de la suppression de la séance: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}         
          /**
 * @OA\Post(
 *      path="/api/seance/AccepterPourCandidat/{id}",
 *      operationId="accepterPourCandidat",
 *      tags={"Seances"},
 *      summary="Accepter une séance pour le candidat",
 *      description="Accepter une séance pour le candidat en mettant à jour l'attribut candidat_status",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de la séance à accepter",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Attribut candidat_status mis à jour avec succès pour la séance",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Attribut candidat_status mis à jour avec succès pour la séance"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Séance non trouvée. Impossible de mettre à jour l'attribut candidat_status"
 *      )
 * )
 */




 public function AccepterSeance($id)
{
    $user = Auth::user();

    $seance = Seance::find($id);

    if (!$seance) {
        return response()->json(["error" => "La séance n'existe pas."], 404);
    }

    try {
        if ($user->role === 'candidat') {
            $seance->candidat_status = "confirmee";
            if ($seance->moniteur_status === "confirmee") {
                $seance->status = "confirmee";
            } else if ($seance->moniteur_status === "refusee") {
                $seance->status = "refusee";
            } else {
                $seance->status = "en attente";
            }
        } elseif ($user->role === 'moniteur') {
            $seance->moniteur_status = "confirmee";
            if ($seance->candidat_status === "confirmee") {
                $seance->status = "confirmee";
            } else if ($seance->candidat_status === "refusee") {
                $seance->status = "refusee";
            } else {
                $seance->status = "en attente";
            }
        } else {
            return response()->json(["error" => "L'utilisateur n'est pas autorisé à accéder à cette ressource."], 403);
        }
        
        $seance->save();

        return response()->json(["seance" => $seance], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la modification de la séance: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}


public function RefuserSeance($id)
{
    $user = Auth::user();

    $seance = Seance::find($id);

    if (!$seance) {
        return response()->json(["error" => "La séance n'existe pas."], 404);
    }

    try {
        if ($user->role === 'candidat') {
            $seance->candidat_status = "refusee";
            if ($seance->moniteur_status === "refusee" || $seance->moniteur_status === "confirmee") {
                $seance->status = "refusee";
            } else {
                $seance->status = "en attente";
            }
        } elseif ($user->role === 'moniteur') {
            $seance->moniteur_status = "refusee";
            if ($seance->candidat_status === "refusee" || $seance->candidat_status === "confirmee") {
                $seance->status = "refusee";
            } else {
                $seance->status = "en attente";
            }
        } else {
            return response()->json(["error" => "L'utilisateur n'est pas autorisé à accéder à cette ressource."], 403);
        }
        
        $seance->save();

        return response()->json(["seance" => $seance], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la modification de la séance: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}



public function AccepterPourCandidat($id)
{
    try {
        $seance = Seance::find($id);
        if ($seance) {
            $seance->candidat_status = true;
            $seance->save();
            return response()->json("Attribut 'candidat_status' mis à jour avec succès pour la séance.", 200);
        } else {
            return response()->json("La séance n'a pas été trouvée. Impossible de mettre à jour l'attribut 'candidat_status'.", 404);
        }
    } catch (\Exception $e) {
        $error = "Erreur lors de la mise à jour de l'attribut 'candidat_status' pour la séance: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}


        /**
 * @OA\Post(
 *      path="/api/seance/RefuserPourCandidat/{id}",
 *      operationId="refuserPourCandidat",
 *      tags={"Seances"},
 *      summary="Refuser une séance pour le candidat",
 *      description="Refuser une séance pour le candidat en mettant à jour l'attribut candidat_status à false",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de la séance à refuser",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Attribut candidat_status mis à jour avec succès pour la séance",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Attribut candidat_status mis à jour avec succès pour la séance"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Séance non trouvée. Impossible de mettre à jour l'attribut candidat_status"
 *      )
 * )
 */

 public function RefuserPourCandidat($id)
 {
     try {
         $seance = Seance::find($id);
         if ($seance) {
             $seance->candidat_status = false;
             $seance->save();
             return response()->json("Attribut 'candidat_status' mis à jour avec succès pour la séance.", 200);
         } else {
             return response()->json("La séance n'a pas été trouvée. Impossible de mettre à jour l'attribut 'candidat_status'.", 404);
         }
     } catch (\Exception $e) {
         $error = "Erreur lors de la mise à jour de l'attribut 'candidat_status' pour la séance: " . $e->getMessage();
         return response()->json(["error" => $error], 500);
     }
 }       
      /**
 * @OA\Post(
 *      path="/api/seance/AccepterPourMoniteur/{id}",
 *      operationId="accepterPourMoniteur",
 *      tags={"Seances"},
 *      summary="Accepter une séance pour le moniteur",
 *      description="Accepter une séance pour le moniteur en mettant à jour l'attribut moniteur_status à true",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de la séance à accepter pour le moniteur",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Attribut moniteur_status mis à jour avec succès pour la séance",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Attribut moniteur_status mis à jour avec succès pour la séance"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Séance non trouvée. Impossible de mettre à jour l'attribut moniteur_status"
 *      )
 * )
 */
public function AccepterPourMoniteur($id)
{
    try {
        $seance = Seance::find($id);

        if ($seance) {
            $seance->moniteur_status = true;
            $seance->save();
            return response()->json("Attribut 'moniteur_status' mis à jour avec succès pour la séance.", 200);
        } else {
            return response()->json("La séance n'a pas été trouvée. Impossible de mettre à jour l'attribut 'moniteur_status'.", 404);
        }
    } catch (\Exception $e) {
        $error = "Erreur lors de la mise à jour de l'attribut 'moniteur_status' pour la séance: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}

        
      /**
 * @OA\Post(
 *      path="/api/seance/RefuserPourMoniteur/{id}",
 *      operationId="refuserPourMoniteur",
 *      tags={"Seances"},
 *      summary="Refuser une séance pour le moniteur",
 *      description="Refuser une séance pour le moniteur en mettant à jour l'attribut moniteur_status à false",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de la séance à refuser pour le moniteur",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Attribut moniteur_status mis à jour avec succès pour la séance",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Attribut moniteur_status mis à jour avec succès pour la séance"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Séance non trouvée. Impossible de mettre à jour l'attribut moniteur_status"
 *      )
 * )
 */

 public function RefuserPourMoniteur($id)
 {
     try {
         $seance = Seance::find($id);
 
         if ($seance) {
             $seance->moniteur_status = false;
             $seance->save();
             return response()->json("Attribut 'moniteur_status' mis à jour avec succès pour la séance.", 200);
         } else {
             return response()->json("La séance n'a pas été trouvée. Impossible de mettre à jour l'attribut 'moniteur_status'.", 404);
         }
     } catch (\Exception $e) {
         $error = "Erreur lors de la mise à jour de l'attribut 'moniteur_status' pour la séance: " . $e->getMessage();
         return response()->json(["error" => $error], 500);
     }
 }
 

      /**
 * @OA\Post(
 *      path="/api/seance/updateSeanceStatus/{id}",
 *      operationId="updateSeanceStatus",
 *      tags={"Seances"},
 *      summary="Mettre à jour le statut d'une séance",
 *      description="Mettre à jour le statut d'une séance en fonction des attributs candidat_status et moniteur_status",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de la séance à mettre à jour",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Statut de la séance mis à jour avec succès",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Statut de la séance mis à jour avec succès"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Séance non trouvée. Impossible de mettre à jour le statut de la séance"
 *      )
 * )
 */
    // TODO: Add error handling

    public function updateSeanceStatus($id)
    {
        try {
            $seance = Seance::find($id);
            
            if ($seance) {
                $status = ($seance->candidat_status == '1' && $seance->moniteur_status == '1') ? 'confirmee' : 'refusee';
                
                $seance->status = $status;
                $seance->save();
    
                return response()->json("Statut de la séance mis à jour avec succès", 200);
            } else {
                return response()->json("Séance non trouvée. Impossible de mettre à jour le statut de la séance.", 404);
            }
        } catch (\Exception $e) {
            $error = "Erreur lors de la mise à jour du statut de la séance: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
    
      }