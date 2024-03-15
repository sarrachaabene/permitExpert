<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\User;
use App\Models\Vehicule;

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
  public function index(){
    $seance= Seance::get();
  return response()->json($seance, 200);
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
public function store(Request $request)
  {
      // Trouver l'utilisateur par son ID
      $moniteur = User::find($request->moniteur_id);
      $candidat = User::find($request->candidat_id);
      $vehicule=Vehicule::find($request->vehicule_id);
      if (($candidat && $candidat->role === "candidat")&&($moniteur && $moniteur->role === "moniteur")) {
          $seance = Seance::create([
              'type' => $request->type,
              'heureD' => $request->heureD,
              'heureF'=> $request->heureF,
              'dateS'=> $request->dateS,
              'moniteur_id'=> $request->moniteur_id,
              'candidat_id'=> $request->candidat_id,
              'vehicule_id'=> $request->vehicule_id,
                ]); 
          return response()->json($seance, 200);
      } else {
          return response()->json("User is not an candidat", 400);
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
     public function show($id){
      $seance = Seance::find($id);
      if($seance){
    return response()->json($seance, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);
      }   }

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


      public function ShowSeanceBycandidatId($candidatId)
      {
          $seance = Seance::where('candidat_id', $candidatId)->get();
          return response()->json($seance, 200);
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

      public function ShowSeanceByvehiculeId($vehiculeId)
      {
          $seance = Seance::where('vehicule_id', $vehiculeId)->get();
          return response()->json($seance, 200);
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
      public function ShowSeanceBymoniteurId($moniteurId)
      {
          $seance = Seance::where('moniteur_id', $moniteurId)->get();
          return response()->json($seance, 200);
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

      public function update(Request $request,$id){
        $seance= Seance::find($id);
         if($seance){
           $seance->update($request->all());
           return response()->json($seance, 200);
 
         }else {
           $msg = "User not found";
           return response()->json($msg, 404);
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
         public function delete($id) {
          $seance = Seance::find($id);
                if (!$seance) {
              $msg = "seance not found";
              return response()->json($msg, 404);
          }
      
          if ($seance->delete()) {
              return response()->json("seance deleted successfully", 200);
          } else {
              return response()->json("seance to delete permis", 500);
          }
           }
          
           public function AccepterPourCandidat($id) {
            $seance = Seance::find($id);
        
            if ($seance) {
                $seance->candidat_status = true;
                $seance->save();
                return response()->json("Attribut candidat_status mis à jour avec succès pour la séance", 200);
            } else {
                return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut candidat_status", 404);
            }
        }
        
        public function RefuserPourCandidat($id) {
          $seance = Seance::find($id);
      
          if ($seance) {
              $seance->candidat_status = false;
              $seance->save();
              return response()->json("Attribut candidat_status mis à jour avec succès pour la séance", 200);
          } else {
              return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut candidat_accepte", 404);
          }
      }
        
        public function AccepterPourMoniteur($id) {
          $seance = Seance::find($id);
      
          if ($seance) {
              $seance->moniteur_status= true;
              $seance->save();
              return response()->json("Attribut moniteur_statusmis à jour avec succès pour la séance", 200);
          } else {
              return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut moniteur_status", 404);
          }
      }
        
        public function RefuserPourMoniteur($id) {
          $seance = Seance::find($id);
      
          if ($seance) {
              $seance->moniteur_status = false;
              $seance->save();
              return response()->json("Attribut moniteur_status mis à jour avec succès pour la séance", 200);
          } else {
              return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut moniteur_accepte", 404);
          }
      }

        public function updateSeanceStatus($id)
        {
          $seance = Seance::find($id);
          if($seance->candidat_status=='1'&& $seance->moniteur_status=='0'){
            $seance->status = 'refusee';
            $seance->save();
            return response()->json("Statut de la séance mis à jour avec succès", 200);

          }elseif($seance->candidat_status=='0'&& $seance->moniteur_status=='0'){
            $seance->status = 'refusee';
            $seance->save();
            return response()->json("Statut de la séance mis à jour avec succès", 200);
          }elseif($seance->candidat_status=='1'&& $seance->moniteur_status=='1'){
            $seance->status = 'confirmee';
            $seance->save();
            return response()->json("Statut de la séance mis à jour avec succès", 200);
          }else{
            $seance->status = 'refusee';
            $seance->save();
            return response()->json("Statut de la séance mis à jour avec succès", 200);
          }
        }
      }