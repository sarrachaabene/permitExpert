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
      $user = User::find($request->user_id);
      $user1 = User::find($request->user_id1);
      $vehicule=Vehicule::find($request->vehicule_id);
      if (($user && $user->role === "candidat")&&($user1 && $user1->role === "moniteur")) {
          $seance = Seance::create([
              'type' => $request->type,
              'heureD' => $request->heureD,
              'heureF'=> $request->heureF,
              'dateS'=> $request->dateS,
                ]);
          $user->seance_id = $seance->id;
          $user->save();
          $user1->seance_id = $seance->id;
          $user1->save();
          $vehicule->seance_id = $seance->id;
          $vehicule->save();
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
                $seance->candidat_accepte = true;
                $seance->save();
                return response()->json("Attribut candidat_accepte mis à jour avec succès pour la séance", 200);
            } else {
                return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut candidat_accepte", 404);
            }
        }
        
        public function RefuserPourCandidat($id) {
          $seance = Seance::find($id);
      
          if ($seance) {
              $seance->candidat_accepte = false;
              $seance->save();
              return response()->json("Attribut candidat_accepte mis à jour avec succès pour la séance", 200);
          } else {
              return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut candidat_accepte", 404);
          }
      }
        
        public function AccepterPourMoniteur($id) {
          $seance = Seance::find($id);
      
          if ($seance) {
              $seance->moniteur_accepte = true;
              $seance->save();
              return response()->json("Attribut moniteur_accepte mis à jour avec succès pour la séance", 200);
          } else {
              return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut moniteur_accepte", 404);
          }
      }
        
        public function RefuserPourMoniteur($id) {
          $seance = Seance::find($id);
      
          if ($seance) {
              $seance->moniteur_accepte = false;
              $seance->save();
              return response()->json("Attribut moniteur_accepte mis à jour avec succès pour la séance", 200);
          } else {
              return response()->json("Séance non trouvée. Impossible de mettre à jour l'attribut moniteur_accepte", 404);
          }
      }
        
      /*  public function updateSeanceStatus($id)
        {
            $candidatAccepte = $this->AccepterPourCandidat($id);
            $moniteurAccepte = $this->AccepterPourMoniteur($id);
            $candidatRefuse = $this->RefuserPourCandidat($id);
            $moniteurRefuse = $this->RefuserPourMoniteur($id);
        
            $seance = Seance::find($id);
        
            if ($candidatAccepte && $moniteurAccepte) {
                $seance->status = 'confirmee';
            } elseif ($candidatRefuse && $moniteurAccepte) {
                $seance->status = 'refusee';
            } elseif ($candidatAccepte && $moniteurRefuse) {
                $seance->status = 'refusee';
            } else {
                $seance->status = 'en attente';
            }
        
            $seance->save();
        
            return response()->json("Statut de la séance mis à jour avec succès", 200);
        }*/

        public function updateSeanceStatus($id)
        {
          $seance = Seance::find($id);
          if($seance->candidat_accepte=='1'&& $seance->moniteur_accepte=='0'){
            $seance->status = 'refusee';
            $seance->save();
            return response()->json("Statut de la séance mis à jour avec succès", 200);

          }elseif($seance->candidat_accepte=='0'&& $seance->moniteur_accepte=='0'){
            $seance->status = 'refusee';
            $seance->save();
            return response()->json("Statut de la séance mis à jour avec succès", 200);
          }elseif($seance->candidat_accepte=='1'&& $seance->moniteur_accepte=='1'){
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