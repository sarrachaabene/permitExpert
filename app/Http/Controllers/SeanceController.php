<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;
use App\Models\User;
use App\Models\Vehicule;


class SeanceController extends Controller
{

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

     public function show($id){
      $seance = Seance::find($id);
      if($seance){
    return response()->json($seance, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }


      public function ShowSenaceBycandidatId($candidatId)
      {
          $seance = Seance::where('candidat_id', $candidatId)->get();
          return response()->json($seance, 200);
      }
      public function ShowSenaceByvehiculeId($vehiculeId)
      {
          $seance = Seance::where('vehicule_id', $vehiculeId)->get();
          return response()->json($seance, 200);
      }
      public function ShowSenaceBymoniteurId($moniteurId)
      {
          $seance = Seance::where('moniteur_id', $moniteurId)->get();
          return response()->json($seance, 200);
      }
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