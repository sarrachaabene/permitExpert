<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandeInscription;

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
         'adresseEcole' => 'required|string',
         'descriptionEcole' => 'required|string',
         'nomA' => 'required|string',
         'prenomA' => 'required|string',
         'emailA' => 'required|email',
         'cin' => 'required|string',
         'numTel' => 'required|string',
         'dateNaissance' => 'required|date',
         'status' => 'string',
     ]);
     try {
         $demandeInscription = DemandeInscription::create($validatedData);
          if ($demandeInscription) {
             return response()->json($demandeInscription, 200);
         } else {
             return response()->json("La demande d'inscription n'a pas été créée", 400);
         }
     } catch (\Exception $e) {
         return response()->json("Erreur lors de la création de la demande d'inscription: " . $e->getMessage(), 500);
     }
 }


public function accepteDemande($idDemande){
  $demande = DemandeInscription::find($idDemande);
  if (!$demande) {
      return response()->json("Demande not found", 404);
  }
  $demande->status = true;
  $demande->save();
  $admin = User::create([
       'user_name'=>$demande->nomA,
      'email' => $demande->emailA,
      'cin' => $demande->cin,
      'numTel'=>$demande->numTel,
      'dateNaissance'=>$demande->dateNaissance,
  ]);
  $autoEcole = AutoEcole::create([
      'nom' => $demande->nomEcole,
      'adresse' => $demande->adresseEcole,
      'description' => $demande->descriptionEcole,
  ]);
  $admin->autoEcole()->associate($autoEcole);
  $admin->save();
  return response()->json("Demande accepted successfully", 200);
}
       
    public function Refuser($id) {
  /*  $seance = Seance::find($id);
          if (!$seance) {
        $msg = "seance not found";
        return response()->json($msg, 404);
    }

    if ($seance->delete()) {
        return response()->json("seance deleted successfully", 200);
    } else {
        return response()->json("seance to delete permis", 500);*/
        //refuser fait rien juste les donneé reste 
    }


  public function accepterDemande($idDemande) {
    // find and accept demande
    //  changes status to true ;
    // send email to inform
  }
  public function refuseDemande($idDemande) {
    // find and refuse demande
    //  changes status to false ;
    // send email to inform
  }
}