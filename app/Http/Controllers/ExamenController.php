<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\User;
use App\Models\Vehicule;
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
  public function index(){
    $examen= Examen::get();
  return response()->json($examen, 200);
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
      $user = User::find($request->user_id);
     $vehicule=Vehicule::find($request->vehicule_id);
      if ($user && $user->role=='candidat'&& $vehicule ){
        $examen = Examen::create([
          'type' => $request->type,
          'heureD' => $request->heureD,
          'heureF'=> $request->heureF,
          'dateE'=> $request->dateE,
          'user_id'=> $request->user_id,
          'vehicule_id'=> $request->vehicule_id,
        ]);
        return response()->json($examen, 200);
      }else {
        return response()->json("examen not created", 400);
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

     public function show($id){
      $examen = Examen::find($id);
      if($examen){
    return response()->json($examen, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
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
      public function ShowExamensBycandidatId($candidatId)
      {
          $examen = Examen::where('user_id', $candidatId)->get();
          return response()->json($examen, 200);
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
      public function ShowExamensByvehiculeId($vehiculeId)
      {
          $examen = Examen::where('vehicule_id', $vehiculeId)->get();
          return response()->json($examen, 200);
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
      public function update(Request $request,$id){
       $examen= Examen::find($id);
        if($examen){
          $examen->update($request->all());
          return response()->json($examen, 200);

        }else {
          $msg = "User not found";
          return response()->json($msg, 404);
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
    // Find the user by ID
    $examen = Examen::find($id);

    // Check if the user exists
    if (!$examen) {
        $msg = "Exam not found";
        return response()->json($msg, 404);
    }

    // Delete the user
    $examen->delete();

    // Check if the deletion was successful
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
public function AccepterExamen($id) {
  $examen = Examen::find($id);
  $examen->status = 'confirmee';
  $examen->save();
  return('examen acceptee');

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

 public function RefuserExamen($id) {
  $examen = Examen::find($id);
  $examen->status = 'refusee';
  $examen->save();
  return('examen refusee');

 }
}
