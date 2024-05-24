<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\AutoEcole;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
/**
 * @OA\Schema(
 *     schema="Transaction",
 *     title="Transaction",
 *     description="Transaction model",
 *     @OA\Property(
 *         property="dateT",
 *         type="string",
 *         format="date"
 *     ),
 *     @OA\Property(
 *         property="typeT",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="montantT",
 *         type="number",
 *         format="float"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="auto_ecole_id",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="vehicule_id",
 *         type="integer"
 *     ),
 * )
 */
class TransactionController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/transaction/index",
 *      operationId="getTransactionList",
 *      tags={"Transactions"},
 *      summary="Get list of Transactions",
 *      description="Returns list of Transactions",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Transaction")
 *          ),
 *      ),
 * )
 */
     // TODO: error handling
     public function index()
     {
         $adminId = Auth::id(); 
         $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
         
         try {
             $transactions = Transaction::where('auto_ecole_id', $adminAutoEcoleId)->get();
     
             if ($transactions->isEmpty()) {
                 return response()->json("Aucune transaction trouvée pour cette auto-école.", 404);
             } 
             
             $transactionDetails = [];
             foreach ($transactions as $transaction) {
                 $userDetails = User::find($transaction->user_id);
                 $vehiculeDetails = Vehicule::find($transaction->vehicule_id);
                 $transactionDetails[] = [
                     "id" => $transaction->id,
                     "montantT" => $transaction->montantT,
                     "dateT" => $transaction->dateT,
                     "description" => $transaction->description,
                     "Type_T" => $transaction->Type_T,
                     "Type_Transaction" => $transaction->Type_Transaction,
                     "user_name" => $userDetails ? $userDetails->user_name : "",
                     "immatricule" => $vehiculeDetails ? $vehiculeDetails->immatricule : "",
                 ];
             }
     
             return response()->json($transactionDetails, 200);
         } catch (\Exception $e) {
             $error = "Erreur lors de la récupération des transactions: " . $e->getMessage();
             return response()->json(["error" => $error], 500);
         }
     }
     
/**
 * @OA\Post(
 *     path="/api/transaction/store",
 *     operationId="storeTransaction",
 *     tags={"Transactions"},
 *     summary="Create a new Transaction",
 *     description="Creates a new transaction based on the provided parameters.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Transaction data",
 *         @OA\JsonContent(
 *             required={"Type_T", "montantT", "dateT", "description", "user_id"},
 *             @OA\Property(property="Type_T", type="string"),
 *             @OA\Property(property="montantT", type="number", format="float"),
 *             @OA\Property(property="dateT", type="string", format="date"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="auto_ecole_id", type="integer"),
 *             @OA\Property(property="vehicule_id", type="integer"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="Type_T", type="string"),
 *             @OA\Property(property="montantT", type="number", format="float"),
 *             @OA\Property(property="dateT", type="string", format="date"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="auto_ecole_id", type="integer"),
 *             @OA\Property(property="vehicule_id", type="integer"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *     ),
 * )
 */
      // TODO: Add validation and error handling test sur le role d'utilisateur est si l'autoecole appartient a lui
      public function store(Request $request)
      {
          try {
              $adminId = Auth::id();
              $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;
      
              $validatedData = $request->validate([
                  'Type_T' => 'required|in:vehicule,utilisateur,general',
                  'Type_Transaction' => 'required|in:flux entrant,flux sortant',
                  'montantT' => 'required|numeric',
                  'description' => 'required',
                  'user_name' => 'required_if:Type_T,utilisateur|exists:users,user_name',
                  'immatricule' => 'required_if:Type_T,vehicule|exists:vehicules,immatricule',
              ]);
      
              $userId = null;
              $vehiculeId = null;
      
              if ($validatedData['Type_T'] === 'vehicule') {
                  $vehicule = Vehicule::where('immatricule', $validatedData['immatricule'])->firstOrFail();
                  $vehiculeId = $vehicule->id;
      
                  if ($vehicule->auto_ecole_id !== $adminAutoEcoleId) {
                      return response()->json(["error" => "Le véhicule n'appartient pas à la même auto-école que l'administrateur."], 400);
                  }
              }
      
              if ($validatedData['Type_T'] === 'utilisateur') {
                  $user = User::where('user_name', $validatedData['user_name'])->firstOrFail();
                  $userId = $user->id;
      
                  if (!in_array($user->role, ['moniteur', 'candidat'])) {
                      return response()->json(["error" => "L'utilisateur doit être un moniteur ou un candidat."], 400);
                  }
      
                  if ($user->auto_ecole_id !== $adminAutoEcoleId) {
                      return response()->json(["error" => "L'utilisateur n'appartient pas à la même auto-école que l'administrateur."], 400);
                  }
              }
      
              $transaction = Transaction::create([
                  'Type_T' => $validatedData['Type_T'],
                  'montantT' => $validatedData['montantT'],
                  'dateT' => now(),
                  'Type_Transaction' => $validatedData['Type_Transaction'],
                  'description' => $validatedData['description'],
                  'user_id' => $userId,
                  'vehicule_id' => $vehiculeId,
                  'auto_ecole_id' => $adminAutoEcoleId,
              ]);
      
              return response()->json($transaction, 200);
          } catch (\Exception $e) {
              $error = "Erreur lors de la création de la transaction : " . $e->getMessage();
              return response()->json(["error" => $error], 500);
          }
      }
      
            
  /**
 * @OA\Get(
 *      path="/api/transaction/show/{id}",
 *      operationId="getTransactionById",
 *      tags={"Transactions"},
 *      summary="Get a Transaction by ID",
 *      description="Returns a Transaction by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Transaction to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Transaction"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Transaction not found"
 *          ),
 *      ),
 * )
 */
    // TODO: not found 404
    public function show($id)
    {
        try {
            $transaction = Transaction::find($id);      
            if (!$transaction) {
                $msg = "La transaction avec l'ID spécifié n'a pas été trouvée.";
                return response()->json(["error" => $msg], 404);
            }
            return response()->json($transaction, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération de la transaction: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }    
/**
 * @OA\Get(
 *      path="/api/transaction/ShowTransactionByuserId/{userId}",
 *      operationId="getTransactionByUserId",
 *      tags={"Transactions"},
 *      summary="Get transactions by User ID",
 *      description="Returns transactions associated with a specific User by their ID",
 *      @OA\Parameter(
 *          name="userId",
 *          in="path",
 *          description="ID of the User",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Transaction")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="No transactions found for the User"
 *          ),
 *      ),
 * )
 */
    // TODO: error handling

    public function showTransactionByUserId($userId)
    {
        try {
            $transactions = Transaction::where('user_id', $userId)->get();
            if ($transactions->isEmpty()) {
                $msg = "Aucune transaction trouvée pour l'utilisateur avec l'ID spécifié.";
                return response()->json(["error" => $msg], 404);
            }
            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des transactions pour l'utilisateur: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }  
    public function showTransactionByUserIdForMoniteurAndCandidat()
    {
        try {
            if (!Auth::check()) {
                return response()->json(["error" => "Utilisateur non authentifié."], 401);
            }
            $userId = Auth::id();
            $transactions = Transaction::where('user_id', $userId)->get();
            if ($transactions->isEmpty()) {
                $msg = "Aucune transaction trouvée pour l'utilisateur avec l'ID spécifié.";
                return response()->json(["error" => $msg], 404);
            }
            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des transactions pour l'utilisateur: " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
    
    /**
 * @OA\Get(
 *      path="/api/transaction/ShowTransactionByvehiculeId/{vehiculeId}",
 *      operationId="getTransactionByVehiculeId",
 *      tags={"Transactions"},
 *      summary="Get transactions by Vehicule ID",
 *      description="Returns transactions associated with a specific Vehicule by its ID",
 *      @OA\Parameter(
 *          name="vehiculeId",
 *          in="path",
 *          description="ID of the Vehicule",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Transaction")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="No transactions found for the Vehicule"
 *          ),
 *      ),
 * )
 */
public function showTransactionByVehiculeId($vehiculeId)
{
    try {
        $transactions = Transaction::where('vehicule_id', $vehiculeId)->get();      
        if ($transactions->isEmpty()) {
            $msg = "Aucune transaction trouvée pour le véhicule avec l'ID spécifié.";
            return response()->json(["error" => $msg], 404);
        }
        return response()->json($transactions, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la récupération des transactions pour le véhicule: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
    /**
 * @OA\Get(
 *      path="/api/transaction/ShowTransactionByautoecoleId/{ecoleId}",
 *      operationId="getTransactionByAutoEcoleId",
 *      tags={"Transactions"},
 *      summary="Get transactions by Auto-Ecole ID",
 *      description="Returns transactions associated with a specific Auto-Ecole by its ID",
 *      @OA\Parameter(
 *          name="ecoleId",
 *          in="path",
 *          description="ID of the Auto-Ecole",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Transaction")
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="No transactions found for the Auto-Ecole"
 *          ),
 *      ),
 * )
 */
    // TODO:error handling

    public function showTransactionByAutoEcoleId($ecoleId)
    {
        try {
            $transactions = Transaction::where('auto_ecole_id', $ecoleId)->get();
            
            if ($transactions->isEmpty()) {
                $msg = "Aucune transaction trouvée pour l'auto-école avec l'ID spécifié.";
                return response()->json(["error" => $msg], 404);
            }
    
            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            $error = "Erreur lors de la récupération des transactions pour l'auto-école : " . $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }
    
    /**
 * @OA\Put(
 *      path="/api/transaction/update/{id}",
 *      operationId="updateTransaction",
 *      tags={"Transactions"},
 *      summary="Update a Transaction",
 *      description="Updates an existing Transaction",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Transaction to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/Transaction")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Transaction"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Transaction not found"
 *          ),
 *      ),
 * )
 */
    // TODO: Add validation and error handling
    public function update(Request $request, $id)
{
    try {
        $adminId = Auth::id();
        $adminAutoEcoleId = User::findOrFail($adminId)->auto_ecole_id;

        $transaction = Transaction::findOrFail($id);
        $validatedData = $request->validate([
            'Type_T' => 'in:vehicule,utilisateur,general',
            'Type_Transaction' => 'in:flux entrant,flux sortant',
            'montantT' => 'numeric',
            'description' => 'string',
            'user_name' => [
                'required_if:Type_T,utilisateur',
                Rule::exists('users', 'user_name')->where(function ($query) use ($adminAutoEcoleId) {
                    $query->where('auto_ecole_id', $adminAutoEcoleId);
                }),
            ],
            'immatricule' => [
                'required_if:Type_T,vehicule',
                Rule::exists('vehicules', 'immatricule')->where(function ($query) use ($adminAutoEcoleId) {
                    $query->where('auto_ecole_id', $adminAutoEcoleId);
                }),
            ],
        ]);

        $userId = null;
        $vehiculeId = null;

        if (isset($validatedData['immatricule'])) {
            $vehicule = Vehicule::where('immatricule', $validatedData['immatricule'])->firstOrFail();
            $vehiculeId = $vehicule->id;
        }

        if (isset($validatedData['user_name'])) {
            $user = User::where('user_name', $validatedData['user_name'])->firstOrFail();
            $userId = $user->id;
        }
        $transaction->update([
          'Type_T' => $validatedData['Type_T'] ?? $transaction->Type_T,
          'montantT' => $validatedData['montantT'] ?? $transaction->montantT,
          'Type_Transaction' => $validatedData['Type_Transaction'] ?? $transaction->Type_Transaction,
          'description' => $validatedData['description'] ?? $transaction->description,
          'user_id' => $validatedData['Type_T'] === 'utilisateur' ? $userId ?? $transaction->user_id : null,
          'vehicule_id' => $validatedData['Type_T'] === 'vehicule' ? $vehiculeId ?? $transaction->vehicule_id : null,
          'auto_ecole_id' => $adminAutoEcoleId,
          'dateT' => now(),
      ]);

        return response()->json($transaction, 200);
    } catch (\Exception $e) {
        $error = "Erreur lors de la mise à jour de la transaction : " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
       /**
 * @OA\Delete(
 *      path="/api/transaction/delete/{id}",
 *      operationId="deleteTransaction",
 *      tags={"Transactions"},
 *      summary="Delete a Transaction",
 *      description="Deletes a Transaction by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Transaction to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Transaction deleted successfully"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Transaction not found"
 *          ),
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal Server Error",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Failed to delete Transaction"
 *          ),
 *      ),
 * )
 */
public function delete($id)
{
    try {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            $msg = "Transaction not found";
            return response()->json($msg, 404);
        }
        $deleted = $transaction->delete();
        if ($deleted) {
            return response()->json("Transaction deleted successfully", 200);
        } else {
            return response()->json("Failed to delete transaction", 500);
        }
    } catch (\Exception $e) {
        $error = "Error while deleting the transaction: " . $e->getMessage();
        return response()->json(["error" => $error], 500);
    }
}
}
