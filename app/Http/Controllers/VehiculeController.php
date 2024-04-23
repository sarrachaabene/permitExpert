<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;
 /**
     * @OA\Schema(
     *     schema="Vehicule",
     *     title="Vehicule",
     *     description="Vehicule model",
     *     @OA\Property(
     *         property="immatricule",
     *         type="string"
     *     ),
     *     @OA\Property(
     *         property="kilometrage",
     *         type="integer"
     *     ),
     *     @OA\Property(
     *         property="marque",
     *         type="string"
     *     ),
     *     @OA\Property(
     *         property="typeV",
     *         type="string"
     *     ),
     * )
     */


class VehiculeController extends Controller
{
/**
 * @OA\Get(
 *      path="/api/vehicule/index",
 *      operationId="getVehiculeList",
 *      tags={"Vehicules"},
 *      summary="Get list of vehicles",
 *      description="Returns list of vehicles",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Vehicule")
 *          ),
 *      ),
 * )
 */
  public function index(){
    $vehicule= Vehicule::get();
  return response()->json($vehicule, 200);
  }
  /**
 * @OA\Post(
 *      path="/api/vehicule/store",
 *      operationId="storeVehicule",
 *      tags={"Vehicules"},
 *      summary="Store a new vehicle",
 *      description="Stores a new vehicle in the database",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Vehicle data",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Vehicule"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Vehicule"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Vehicle not created"
 *          ),
 *      ),
 * )
 */

    public function store(Request $request)
    {
      $vehicule= Vehicule::create($request->all()); 
      if($vehicule)
      {
        return response()->json($vehicule, 200);
      }
      return response()->json("vehicule not created", 400);
     }


/**
 * @OA\Get(
 *      path="/api/vehicule/show/{id}",
 *      operationId="showVehicule",
 *      tags={"Vehicules"},
 *      summary="Show details of a specific vehicle",
 *      description="Retrieves details of a specific vehicle by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the vehicle to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Vehicule"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Vehicle not found"
 *          ),
 *      ),
 * )
 */
     public function show($id){
      $vehicule = Vehicule::find($id);
      if($vehicule){
    return response()->json($vehicule, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
      /**
 * @OA\Put(
 *      path="/api/vehicule/update/{id}",
 *      operationId="updateVehicule",
 *      tags={"Vehicules"},
 *      summary="Update an existing vehicle",
 *      description="Updates an existing vehicle in the database",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the vehicle to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Vehicle data",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Vehicule"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Vehicule"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Vehicle not found"
 *          ),
 *      ),
 * )
 */
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
    $vehicle = Vehicule::find($id);
    if (!$vehicle) {
        $msg = "Vehicle not found";
        return response()->json($msg, 404);
    }
    try {
        $vehicle->delete();
        return response()->json("Vehicle deleted successfully", 200);
    } catch (\Exception $e) {
        return response()->json("Failed to delete vehicle", 500);
    }
}



/* public function deletee($id) {
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
} */



}
