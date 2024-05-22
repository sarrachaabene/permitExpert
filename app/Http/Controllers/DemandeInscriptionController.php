<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeInscription;
use App\Models\User;
use App\Models\Role; 
use App\Models\AutoEcole;
use Illuminate\Support\Facades\Mail;
use App\Notifications\AccepterDemande;
use App\Notifications\RefuserDemande;

/**
 * @OA\Schema(
 *     schema="DemandeInscription",
 *     title="Demande Inscription",
 *     description="Demande Inscription model",
 *     @OA\Property(
 *         property="nomEcole",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="adresseEcole",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="descriptionEcole",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="imageEcole",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="nomA",
 *         type="string"
 *     ), 
 *     @OA\Property(
 *         property="prenomA",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="emailA",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="cin",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="numTel",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="imageA",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="dateNaissance",
 *         type="string"
 *     ),
 * )
 */
class DemandeInscriptionController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/demandeInscript/index",
     *      operationId="getDemandeInscriptionsList",
     *      tags={"Demande Inscriptions"},
     *      summary="Get list of Demande Inscriptions",
     *      description="Returns list of Demande Inscriptions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/DemandeInscription")
     *          ),
     *      ),
     * )
     */
    public function index()
    {
        try {
            $demandeInscription = DemandeInscription::get();
            
            if ($demandeInscription->isEmpty()) {
                return response()->json("Aucune demande d'inscription trouvée", 404);
            }
                return response()->json($demandeInscription, 200);
        } catch (\Exception $e) {
            return response()->json("Erreur lors de la récupération des demandes d'inscription: " . $e->getMessage(), 500);
        }
    }

    public function show($id)
{
    try {
        if (!DemandeInscription::where('id', $id)->exists()) {
            return response()->json("La demande d'inscription n'existe pas", 404);
        }
                $demandeInscription = DemandeInscription::findOrFail($id);
        return response()->json($demandeInscription, 200);
    } catch (\Exception $e) {
        return response()->json("Erreur lors de la récupération de la demande d'inscription: " . $e->getMessage(), 500);
    }
}  
/**
 * @OA\Post(
 *      path="/api/demandeInscript/store",
 *      operationId="storeDemandeInscription",
 *      tags={"Demande Inscriptions"},
 *      summary="Create a new Demande Inscription",
 *      description="Create a new Demande Inscription",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/DemandeInscription")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/DemandeInscription")
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *      )
 * )
 */

 public function store(Request $request)
{
    $validatedData = $request->validate([
        'nomEcole' => 'required|string',
        'aderesseEcole' => 'required|string',
        'descriptionEcole' => 'required|string',
        'user_nameA' => 'required|string',
        'emailA' => 'required|email',
        'cin' => 'required|string',
        'numTel' => 'required|string',
        'dateNaissance' => 'required|date',
        'status' => 'string',
    ]);
    try {
        $demandeInscription = DemandeInscription::create($validatedData);
        if ($demandeInscription) {
            return response()->json($demandeInscription, 201); 
        } else {
            return response()->json("La demande d'inscription n'a pas été créée", 400);
        }
    } catch (\Exception $e) {
        return response()->json("Erreur lors de la création de la demande d'inscription: " . $e->getMessage(), 500);
    }
}


 public function accepteDemande($idDemande, Request $request) {
  $demande = DemandeInscription::find($idDemande);
    if (!$demande) {
      return response()->json("Demande not found", 404);
  }
    $demande->status = 'confirmee';
  $demande->save();
    $admin = User::create([
      'user_name' => $demande->user_nameA,
      'email' => $demande->emailA,
      'cin' => $demande->cin,
      'numTel' => $demande->numTel,
      'dateNaissance' => $demande->dateNaissance,
      'role' => 'admin'
  ]);
  $defaultRole = $request->input('role', 'admin'); 
  $admin->assignRole($defaultRole);
    $autoEcole = AutoEcole::create([
      'nom' => $demande->nomEcole,
      'adresse' => $demande->aderesseEcole,
      'description' => $demande->descriptionEcole,
  ]);
    $admin->autoEcole()->associate($autoEcole);
  $admin->save();
    $admin->notify(new AccepterDemande('Votre demande a été acceptée.'));
    $demande->delete();
    return response()->json("Demande accepted successfully", 200);
}



public function refuseDemande($idDemande) {
  $demande = DemandeInscription::find($idDemande);
  if (!$demande) {
      return response()->json("Demande not found", 404);
  }
  $user = User::create([
    'user_name' => $demande->user_nameA,
    'email' => $demande->emailA,
    'cin' => $demande->cin,
    'numTel' => $demande->numTel,
    'dateNaissance' => $demande->dateNaissance,
]);
$demande->status = 'refusee';


   $demande->save();
  $user->save();
  $user->notify(new RefuserDemande('Votre demande a été refusée.'));
  return response()->json("Demande refusée successfully", 200);
}


}