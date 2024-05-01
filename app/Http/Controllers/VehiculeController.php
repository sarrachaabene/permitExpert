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
    // TODO: error handling
    public function index()
    {
        try {
            $vehicules = Vehicule::get();
    
            if ($vehicules->isEmpty()) {
                return response()->json("Aucun véhicule trouvé.", 404);
            } 
            return response()->json($vehicules, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des véhicules: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }   
    public function CountVehicule()
{
    try {
        $nombreDeVehicules = Vehicule::count();

        if ($nombreDeVehicules === 0) {
            return response()->json("Aucun véhicule trouvé.", 404);
        } 

        return response()->json([
            "nombre_de_vehicules" => $nombreDeVehicules
        ], 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des véhicules: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
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
     // TODO: Add validation and error handling
     public function store(Request $request)
     {
         try {
             $validatedData = $request->validate([
                 'immatricule' => 'required|unique:vehicules',
                 'kilometrage' => 'required|numeric',
                 'marque' => 'required',
                 'typeV' => ['required', \Illuminate\Validation\Rule::in(['voiture', 'moto', 'camion'])],
             ]);
                  $vehicule = Vehicule::create($validatedData);
             if ($vehicule) {
                 return response()->json($vehicule, 200);
             } else {
                 return response()->json("Erreur lors de la création du véhicule.", 400);
             }
         } catch (\Exception $e) {
             $error = "Erreur lors de la création du véhicule: " . $e->getMessage();
             return response()->json(["error" => $error], 500);
         }
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
    // TODO: changer le code 404
    public function show($id)
    {
        try {
            $vehicule = Vehicule::find($id);
                if (!$vehicule) {
                $msg = "Véhicule non trouvé.";
                return response()->json(["error" => $msg], 404);
            }
                return response()->json($vehicule, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération du véhicule: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }    
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
    // TODO: Add validation and error handling
    public function update(Request $request, $id)
    {
        try {
            $vehicule = Vehicule::find($id);
                if (!$vehicule) {
                $msg = "Véhicule non trouvé.";
                return response()->json(["error" => $msg], 404);
            }
                $validatedData = $request->validate([
                'immatricule' => 'required|unique:vehicules,immatricule,' . $id,
                'kilometrage' => 'required|numeric',
                'marque' => 'required',
                'typeV' => ['required', \Illuminate\Validation\Rule::in(['voiture', 'moto', 'camion'])],
            ]);
                $vehicule->update($validatedData);
            return response()->json($vehicule, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la mise à jour du véhicule: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
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
}
