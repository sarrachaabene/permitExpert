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
 *         property="passwordA",
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
    $demandeInscription=DemandeInscription::get();
    return response()->json($demandeInscription,200);
  }

  public function store(Request $request)
  {
    $demandeInscription= DemandeInscription::create($request->all());
    if($demandeInscription)
    { 
      $demandeInscription->save();
      return response()->json($demandeInscription, 200);
    }
    return response()->json("demandeInscription not created", 400);
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


  public function accepteDemande($idDemande)
  {
/*  // Trouver la demande d'inscription par son ID
    $demande = DemandeInscription::find($idDemande);
    // Vérifier si la demande existe
    if (!$demande) {
        return response()->json("Demande not found", 404);
    }

    // Mettre à jour le statut de la demande à true
    $demande->status = true;
    $demande->save();

    // Créer un nouvel utilisateur admin
    $admin = User::create([
        'nom' => $demande->nomA,
        'prenom' => $demande->prenomA,
        'email' => $demande->emailA,
        'password' => bcrypt($demande->passwordA), // Assurez-vous de hasher le mot de passe
        // Ajoutez d'autres champs d'utilisateur si nécessaire
    ]);
    // Créer une nouvelle auto-école associée à l'admin
    $autoEcole = AutoEcole::create([
        'nom' => $demande->nomEcole,
        'adresse' => $demande->adresseEcole,
        'description' => $demande->descriptionEcole,
        // Ajoutez d'autres champs d'auto-école si nécessaire
    ]);

    // Associer l'auto-école créée à l'admin
    $admin->autoEcole()->associate($autoEcole);
    $admin->save();

    return response()->json("Demande accepted successfully", 200);
*/
  }

  public function register($request)
  {/*
    


*/
  }
}