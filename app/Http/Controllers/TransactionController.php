<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\AutoEcole;
use App\Models\Vehicule;
class TransactionController extends Controller
{
  public function index()
  {
    $transaction=Transaction::get();
    return response()->json($transaction,200);
  }
  public function store(Request $request)
  {
      $user = User::find($request->user_id);
      $autoecole = AutoEcole::find($request->auto_ecole_id);
      $vehicule=Vehicule::find($request->vehicule_id);
  if ($user ){
        $transaction = Transaction::create([
          'typeT' => $request->typeT,
          'montantT' => $request->montantT,
          'dateT'=> $request->dateT,
          'description'=> $request->description,
          'user_id'=> $request->user_id,
        ]);
        $transaction->save();
      }elseif ($autoecole)
      {
        $transaction = Transaction::create([
          'typeT' => $request->typeT,
          'montantT' => $request->montantT,
          'dateT'=> $request->dateT,
          'auto_ecole_id'=> $request->auto_ecole_id,
          'description'=> $request->description,
        ]);
        return response()->json($transaction, 200);
        $transaction->save();
      }
      elseif ($vehicule)
      {
        $transaction = Transaction::create([
          'typeT' => $request->typeT,
          'montantT' => $request->montantT,
          'dateT'=> $request->dateT,
          'description'=> $request->description,
          'vehicule_id'=> $request->vehicule_id,

        ]);
        $transaction->save();
      }
        return response()->json($transaction, 200);
  }



  /*public function store(Request $request)
  {
    $vehicule = Vehicule::find($id);
    $user = User::find($id);
    if($user&&$vehicule){
      // Créer un nouvel objet Paiement avec les données de la requête
    $transaction = Transaction::create($request->all());
      // Retourner l'objet Paiement créé avec le code de statut 200
      return response()->json($transaction, 200);
  }
}*/

   public function show($id){
    $transaction = Transaction::find($id);
    if($transaction){
  return response()->json($transaction, 200);

    }else{
      $msg="votre id n'est pas trouve";
          return response()->json($msg, 200);



    }   }

    public function ShowTransactionByuserId($userId)
    {
        $transaction = Transaction::where('user_id', $userId)->get();
        return response()->json($transaction, 200);
    }
    public function ShowTransactionByvehiculeId($vehiculeId)
    {
        $transaction = Transaction::where('vehicule_id', $vehiculeId)->get();
        return response()->json($transaction, 200);
    }
    public function ShowTransactionByautoecoleId($ecoleId)
    {
        $transaction = Transaction::where('auto_ecole_id', $ecoleId)->get();
        return response()->json($transaction, 200);
    }
    public function update(Request $request,$id){
      $transaction= Transaction::find($id);
       if($transaction){
         $transaction->update($request->all());
         return response()->json($transaction, 200);

       }else {
         $msg = "paiement not found";
         return response()->json($msg, 404);
     }
       }
}
