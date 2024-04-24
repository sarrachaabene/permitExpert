<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\RessourceEducative;

/**
 * @OA\Schema(
 *     schema="RessourceEducative",
 *     title="RessourceEducative",
 *     description="Modèle de ressource éducative",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         format="int64",
 *         description="ID de la ressource éducative"
 *     ),
 *     @OA\Property(
 *         property="titreR",
 *         type="string",
 *         description="Titre de la ressource éducative"
 *     ),
 *     @OA\Property(
 *         property="descriptionR",
 *         type="string",
 *         description="Description de la ressource éducative"
 *     ),
 *     @OA\Property(
 *         property="typeR",
 *         type="string",
 *         description="Type de la ressource éducative"
 *     ),
 *     @OA\Property(
 *         property="dateD",
 *         type="string",
 *         format="date",
 *         description="Date de la ressource éducative"
 *     ),
 *     required={"titreR", "descriptionR", "typeR", "dateD"}
 * )
 */
class RessourceEducativeController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/ressourceeducative/index",
 *      operationId="getAllRessources",
 *      tags={"Ressources Educatives"},
 *      summary="Get all Ressources Educatives",
 *      description="Renvoie toutes les ressources éducatives disponibles",
 *      @OA\Response(
 *          response=200,
 *          description="Liste des ressources éducatives",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/RessourceEducative")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Erreur interne du serveur"
 *      )
 * )
 */
    // TODO: Add  error handling

  public function index()
  {
    $ressourceEducative=RessourceEducative::get();
    return response()->json($ressourceEducative,200);
  }
  /**
 * @OA\Post(
 *      path="/api/ressourceeducative/store",
 *      operationId="createRessourceEducative",
 *      tags={"Ressources Educatives"},
 *      summary="Create a new Ressource Educative",
 *      description="Create a new Ressource Educative record",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Ressource Educative object",
 *          @OA\JsonContent(ref="#/components/schemas/RessourceEducative")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Ressource Educative created successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="id", type="integer", format="int64", description="ID of the newly created Ressource Educative"),
 *              @OA\Property(property="titreR", type="string", description="Titre of the newly created Ressource Educative"),
 *              @OA\Property(property="descriptionR", type="string", description="Description of the newly created Ressource Educative"),
 *              @OA\Property(property="typeR", type="string", description="Type of the newly created Ressource Educative"),
 *              @OA\Property(property="dateD", type="string", format="date", description="Date of the newly created Ressource Educative")
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Failed to create Ressource Educative"
 *      )
 * )
 */

     // TODO: Add validation and error handling

  public function store(Request $request)
  {
    $ressourceEducative= RessourceEducative::create($request->all());
    if($ressourceEducative)
    { 
      $ressourceEducative->save();
      return response()->json($ressourceEducative, 200);
    }
    return response()->json("ressourceEducative not created", 400);
   }
   /**
 * @OA\Get(
 *      path="/api/ressourceeducative/show/{id}",
 *      operationId="getRessourceById",
 *      tags={"Ressources Educatives"},
 *      summary="Get a Ressource Educative by ID",
 *      description="Récupère une ressource éducative par son ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID de la ressource éducative à récupérer",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Ressource éducative récupérée avec succès",
 *          @OA\JsonContent(ref="#/components/schemas/RessourceEducative")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Ressource éducative non trouvée"
 *      )
 * )
 */
    // TODO: 404 !!

   public function show($id){
    $ressourceEducative = RessourceEducative::find($id);
    if($ressourceEducative){
  return response()->json($ressourceEducative, 200);

    }else{
      $msg="votre id n'est pas trouve";
          return response()->json($msg, 200);



    }   }
    /**
 * @OA\Put(
 *      path="/api/ressourceeducative/update/{id}",
 *      operationId="updateRessourceEducative",
 *      tags={"Ressources Educatives"},
 *      summary="Update a Ressource Educative",
 *      description="Update an existing Ressource Educative record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Ressource Educative to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Updated Ressource Educative object",
 *          @OA\JsonContent(ref="#/components/schemas/RessourceEducative")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Ressource Educative updated successfully",
 *          @OA\JsonContent(ref="#/components/schemas/RessourceEducative")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Ressource Educative not found"
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Failed to update Ressource Educative"
 *      )
 * )
 */
    // TODO: Add validation and error handling
    public function update(Request $request,$id){
      $ressourceEducative= RessourceEducative::find($id);
       if($ressourceEducative){
         $ressourceEducative->update($request->all());
         return response()->json($ressourceEducative, 200);

       }else {
         $msg = "ressourceEducative not found";
         return response()->json($msg, 404);
     }
       }

       /**
 * @OA\Delete(
 *      path="/api/ressourceeducative/delete/{id}",
 *      operationId="deleteRessourceEducative",
 *      tags={"Ressources Educatives"},
 *      summary="Delete a Ressource Educative",
 *      description="Delete an existing Ressource Educative record",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Ressource Educative to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Ressource Educative deleted successfully",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Ressource Educative deleted successfully"
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Ressource Educative not found"
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Failed to delete Ressource Educative"
 *      )
 * )
 */
       public function delete($id) {
        $ressourceEducative = RessourceEducative::find($id);
              if (!$ressourceEducative) {
            $msg = "ressourceEducative not found";
            return response()->json($msg, 404);
        }
    
        if ($ressourceEducative->delete()) {
            return response()->json("ressourceEducative deleted successfully", 200);
        } else {
            return response()->json("impossible to delete ressourceEducative", 500);
        }
}
}
