<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\User;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Auth;
/**
 * @OA\Schema(
 *     schema="Examen",
 *     title="Examen",
 *     description="Examen model",
 *     @OA\Property(
 *         property="type",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="heureD",
 *         type="string",
 *         format="time"
 *     ),
 *     @OA\Property(
 *         property="heureF",
 *         type="string",
 *         format="time"
 *     ),
 *     @OA\Property(
 *         property="dateE",
 *         type="string",
 *         format="date"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="vehicule_id",
 *         type="integer"
 *     ),
 * )
 */

class ExamenController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/Examen/index",
 *      operationId="getExamenList",
 *      tags={"Examens"},
 *      summary="Get list of Exams",
 *      description="Returns list of Exams",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Examen")
 *          ),
 *      ),
 * )
 */

public function index()
{    $adminId = Auth::id(); 

    try {
        $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
                $examens = Examen::where('auto_ecole_id', $adminAutoEcoleId)->get();
        if ($examens->isEmpty()) {
            return response()->json(["message" => "Aucun examen trouvé pour cette auto-école"], 404);
        }      
        return response()->json($examens, 200);
    } catch (\Exception $e) {
        return response()->json(["error" => "Erreur lors de la récupération des examens: " . $e->getMessage()], 500);
    }
}


/**
 * @OA\Post(
 *      path="/api/Examen/store",
 *      operationId="storeExamen",
 *      tags={"Examens"},
 *      summary="Store a new exam",
 *      description="Stores a new exam in the database",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Exam data",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Examen"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Examen"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam not created"
 *          ),
 *      ),
 * )
 */

 public function store(Request $request)
 {
     $adminId = Auth::id(); 
     $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
     
     try {
         $validatedData = $request->validate([
             'type' => 'required|string',
             'heureD' => 'required|date_format:H:i',
             'heureF' => 'required|date_format:H:i|after:heureD',
             'dateE' => 'required|date',
             'candidat_nom' => 'required|exists:users,user_name,role,candidat,auto_ecole_id,'.$adminAutoEcoleId,
             'vehicule_immatriculation' => 'required|exists:vehicules,immatricule,auto_ecole_id,'.$adminAutoEcoleId,
         ]);
 
         // Trouver les IDs des candidats et des véhicules
         $candidatId = User::where('user_name', $validatedData['candidat_nom'])->pluck('id')->first();
         $vehiculeId = Vehicule::where('immatricule', $validatedData['vehicule_immatriculation'])->pluck('id')->first();
 
         $existingExamen = Examen::where('user_id', $candidatId)
             ->where('dateE', $validatedData['dateE'])
             ->where(function ($query) use ($validatedData) {
                 $query->whereBetween('heureD', [$validatedData['heureD'], $validatedData['heureF']])
                       ->orWhereBetween('heureF', [$validatedData['heureD'], $validatedData['heureF']]);
             })
             ->exists();
 
         if ($existingExamen) {
             return response()->json(["error" => "L'utilisateur a déjà un examen planifié pour cette période."], 400);
         }
 
         $existingExamenVehicule = Examen::where('vehicule_id', $vehiculeId)
             ->where('dateE', $validatedData['dateE'])
             ->where(function ($query) use ($validatedData) {
                 $query->whereBetween('heureD', [$validatedData['heureD'], $validatedData['heureF']])
                       ->orWhereBetween('heureF', [$validatedData['heureD'], $validatedData['heureF']]);
             })
             ->exists();
 
         if ($existingExamenVehicule) {
             return response()->json(["error" => "Le véhicule a déjà un examen planifié pour cette période."], 400);
         }
 
         $examen = Examen::create([
             'type' => $validatedData['type'],
             'heureD' => $validatedData['heureD'],
             'heureF' => $validatedData['heureF'],
             'dateE' => $validatedData['dateE'],
             'user_id' => $candidatId,
             'vehicule_id' => $vehiculeId,
             'auto_ecole_id' => $adminAutoEcoleId,
         ]);
 
         return response()->json($examen, 200);
 
     } catch (\Exception $e) {
         $error = "Erreur lors de la création de l'examen: " . $e->getMessage();
         return response()->json(["error" => $error], 500);
     }
 }
 

  /**
 * @OA\Get(
 *      path="/api/Examen/show/{id}",
 *      operationId="showExamen",
 *      tags={"Examens"},
 *      summary="Show details of a specific exam",
 *      description="Retrieves details of a specific exam by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the exam to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Examen"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam not found"
 *          ),
 *      ),
 * )
 */
 public function show($id)
 {
     try {
         $examen = Examen::find($id);
         
         if (!$examen) {
             $msg = "L'examen avec l'ID spécifié n'a pas été trouvé.";
             return response()->json(["error" => $msg], 404);
         }
         return response()->json($examen, 200);
     } catch (\Exception $e) {
         $error = "Erreur lors de la récupération de l'examen: " . $e->getMessage();
         return response()->json(["error" => $error], 500);
     }
 } 
      /**
 * @OA\Get(
 *      path="/api/Examen/ShowExamensBycandidatId/{id}",
 *      operationId="showExamensByCandidatId",
 *      tags={"Examens"},
 *      summary="Show exams by candidat ID",
 *      description="Retrieves exams associated with a specific candidat by their ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the candidat",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Examen")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="No exams found for the candidat"
 *          ),
 *      ),
 * )
 */
public function showExamensByCandidatId($candidatId)
{
    try {
        $examens = Examen::where('user_id', $candidatId)->get();
        
        if ($examens->isEmpty()) {
            $msg = "Aucun examen trouvé pour le candidat avec l'ID spécifié.";
            return response()->json(["error" => $msg], 404);
        }
        return response()->json($examens, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des examens pour le candidat: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
//Affichage pour candidat

public function showExamensForCandidat($date)
{
    try {
      if (!Auth::check()) {
        return response()->json(["error" => "Utilisateur non authentifié."], 401);
    }
    $candidatId = Auth::id();
        $examens = Examen::where('user_id', $candidatId)
        ->whereDate('dateE', $date)
        ->get();
        if ($examens->isEmpty()) {
            return response()->json(["error" => "Aucun examen trouvée pour le candidat spécifié à cette date."], 404);
        }
        return response()->json($examens, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des examens pour le candidat: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}

      /**
 * @OA\Get(
 *      path="/api/Examen/ShowExamensByvehiculeId/{id}",
 *      operationId="showExamensByVehiculeId",
 *      tags={"Examens"},
 *      summary="Show exams by vehicule ID",
 *      description="Retrieves exams associated with a specific vehicule by their ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the vehicule",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Examen")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="No exams found for the vehicule"
 *          ),
 *      ),
 * )
 */
public function showExamensByVehiculeId($vehiculeId)
{
    try {
        $examens = Examen::where('vehicule_id', $vehiculeId)->get();
        
        if ($examens->isEmpty()) {
            $msg = "Aucun examen trouvé pour le véhicule avec l'ID spécifié.";
            return response()->json(["error" => $msg], 404);
        }

        return response()->json($examens, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des examens pour le véhicule: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
      /**
 * @OA\Put(
 *      path="/api/Examen/update/{id}",
 *      operationId="updateExamen",
 *      tags={"Examens"},
 *      summary="Update an existing exam",
 *      description="Updates an existing exam in the database",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the exam to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Exam data",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Examen"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Examen"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam not found"
 *          ),
 *      ),
 * )
 */
public function update(Request $request, $id)
{
    $adminId = Auth::id(); 
    $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
    
    try {
        $validatedData = $request->validate([
            'type' => 'string',
            'heureD' => 'date_format:H:i',
            'heureF' => 'date_format:H:i|after:heureD',
            'dateE' => 'date',
            'candidat_nom' => 'exists:users,user_name,role,candidat,auto_ecole_id,'.$adminAutoEcoleId,
            'vehicule_immatriculation' => 'exists:vehicules,immatricule,auto_ecole_id,'.$adminAutoEcoleId,
        ]);

        $candidatId = User::where('user_name', $validatedData['candidat_nom'])->pluck('id')->first();
        $vehiculeId = Vehicule::where('immatricule', $validatedData['vehicule_immatriculation'])->pluck('id')->first();

        $existingExamen = Examen::where('id', '!=', $id)
            ->where('user_id', $candidatId)
            ->where('dateE', $validatedData['dateE'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('heureD', [$validatedData['heureD'], $validatedData['heureF']])
                      ->orWhereBetween('heureF', [$validatedData['heureD'], $validatedData['heureF']]);
            })
            ->exists();

        if ($existingExamen) {
            return response()->json(["error" => "L'utilisateur a déjà un examen planifié pour cette période."], 400);
        }

        $existingExamenVehicule = Examen::where('id', '!=', $id)
            ->where('vehicule_id', $vehiculeId)
            ->where('dateE', $validatedData['dateE'])
            ->where(function ($query) use ($validatedData) {
                $query->whereBetween('heureD', [$validatedData['heureD'], $validatedData['heureF']])
                      ->orWhereBetween('heureF', [$validatedData['heureD'], $validatedData['heureF']]);
            })
            ->exists();

        if ($existingExamenVehicule) {
            return response()->json(["error" => "Le véhicule a déjà un examen planifié pour cette période."], 400);
        }

        $examen = Examen::findOrFail($id);
        $examen->update([
            'type' => $validatedData['type'],
            'heureD' => $validatedData['heureD'],
            'heureF' => $validatedData['heureF'],
            'dateE' => $validatedData['dateE'],
            'user_id' => $candidatId,
            'vehicule_id' => $vehiculeId,
            'auto_ecole_id' => $adminAutoEcoleId,
        ]);

        return response()->json($examen, 200);

    } catch (\Exception $e) {
        $error = "Erreur lors de la mise à jour de l'examen: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}

/**
 * @OA\Delete(
 *      path="/api/Examen/delete/{id}",
 *      operationId="deleteExamen",
 *      tags={"Examens"},
 *      summary="Delete an exam",
 *      description="Deletes an exam by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the exam to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam deleted successfully"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam not found"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Failed to delete Exam"
 *          ),
 *      ),
 * )
 */
public function delete($id) {
    $examen = Examen::find($id);
    if (!$examen) {
        $msg = "Exam not found";
        return response()->json($msg, 404);
    }
    $examen->delete();
    if ($examen->trashed()) {
        return response()->json("Exam deleted successfully", 200);
    } else {
        return response()->json("Failed to delete Exam", 500);
    }
}
/**
 * @OA\Post(
 *      path="/api/Examen/AccepterExamen/{id}",
 *      operationId="acceptExamen",
 *      tags={"Examens"},
 *      summary="Accept an exam",
 *      description="Accepts an exam by updating its status to 'confirmee'",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the exam to accept",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam accepted"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam not found"
 *          ),
 *      ),
 * )
 */
public function accepterExamen($id)
{
    try {
        $examen = Examen::find($id);

        if (!$examen) {
            return response()->json(["error" => "L'examen avec l'ID spécifié n'a pas été trouvé."], 404);
        }
        $examen->status = 'confirmee';
        $examen->save();
        return response()->json(["message" => "L'examen a été accepté avec succès."], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de l'acceptation de l'examen: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}

/**
 * @OA\Post(
 *      path="/api/Examen/RefuserExamen/{id}",
 *      operationId="refuseExamen",
 *      tags={"Examens"},
 *      summary="Refuse an exam",
 *      description="Refuses an exam by updating its status to 'refusee'",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the exam to refuse",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam refused"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Exam not found"
 *          ),
 *      ),
 * )
 */

 public function refuserExamen($id)
 {
     try {
         $examen = Examen::find($id);
 
         if (!$examen) {
             return response()->json(["error" => "L'examen avec l'ID spécifié n'a pas été trouvé."], 404);
         }
 
         $examen->status = 'refusee';
         $examen->save();
 
         return response()->json(["message" => "L'examen a été refusé avec succès."], 200);
     } catch (\Exception $e) {
         $error = "Erreur lors du refus de l'examen: " . $e->getMessage();
         return response()->json(["error" => $error], 500);
     }
 }
 
}
