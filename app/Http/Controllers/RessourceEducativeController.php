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
        try {
            $ressources = RessourceEducative::get();
            if ($ressources->isEmpty()) {
                return response()->json(["error" => "Aucune ressource éducative trouvée."], 404);
            }
            return response()->json($ressources, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des ressources éducatives: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
    public function indexx()
    {
        try {
            $ressources = RessourceEducative::all()->groupBy('NSerie')->map(function ($ressources) {
                return $ressources->map(function ($ressource) {
                    return [
                        'titreR' => $ressource->titreR,
                        'Image' => $ressource->Image
                    ];
                });
            })->toArray();
    
            if (empty($ressources)) {
                return response()->json(["error" => "Aucune ressource éducative trouvée."], 404);
            }
    
            return response()->json($ressources, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des ressources éducatives: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
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

 public function store(Request $request)
 {
     try {
         $validatedData = $request->validate([
             'titreR' => 'required|string',
             'descriptionR' => 'required|string',
             'link' => 'required|string|unique:ressource_educatives,link', 
             'NSerie' => 'required|string',
             'Image' => 'required|Image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
         ]);
 
         if ($request->hasFile('Image')) {
             $image = $request->file('Image');
             $imageName = time().'.'.$image->getClientOriginalExtension();
             $image->move(public_path('images'), $imageName);
             $validatedData['Image'] = 'images/'.$imageName; 
         }
 
         $existingRessource = RessourceEducative::where('link', $validatedData['link'])->first();
         if ($existingRessource) {
             return response()->json(["error" => "Une ressource éducative similaire existe déjà."], 400);
         }
 
         $ressourceEducative = RessourceEducative::create([
             'titreR' => $validatedData['titreR'],
             'descriptionR' => $validatedData['descriptionR'],
             'link' => $validatedData['link'],
             'NSerie' => $validatedData['NSerie'],
             'dateD' => isset($validatedData['dateD']) ? $validatedData['dateD'] : now(),
             'Image' => $validatedData['Image'], 
         ]);
 
         return response()->json($ressourceEducative, 200);
     } catch (\Exception $e) {
         $error = "Erreur lors de la création de la ressource éducative: " . $e->getMessage();
         return response()->json(["error" => $error], 500);
     }
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

    public function show($id)
    {
        try {
            $ressourceEducative = RessourceEducative::find($id);
                if ($ressourceEducative) {
                return response()->json($ressourceEducative, 200);
            } else {
                $msg = "La ressource éducative avec l'ID spécifié n'a pas été trouvée.";
                return response()->json(["error" => $msg], 404);
            }
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération de la ressource éducative: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }  
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
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'titreR' => 'sometimes|string',
                'descriptionR' => 'sometimes|string',
                'link' => 'sometimes|string|unique:ressource_educatives,link,' . $id,
                'NSerie' => 'sometimes|string',
                'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $ressourceEducative = RessourceEducative::findOrFail($id);
    
            if ($request->hasFile('Image')) {
                $image = $request->file('Image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $validatedData['Image'] = 'images/' . $imageName;
    
                if ($ressourceEducative->Image) {
                    File::delete(public_path($ressourceEducative->Image));
                }
            } else {
                $validatedData['Image'] = $ressourceEducative->Image;
            }
    
            $ressourceEducative->fill($validatedData);
    
            $ressourceEducative->save();
    
            return response()->json($ressourceEducative, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la mise à jour de la ressource éducative: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
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
