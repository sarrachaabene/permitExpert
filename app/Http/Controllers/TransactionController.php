<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\AutoEcole;
use App\Models\Vehicule;
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

   public function show($id){
    $transaction = Transaction::find($id);
    if($transaction){
  return response()->json($transaction, 200);

    }else{
      $msg="votre id n'est pas trouve";
          return response()->json($msg, 200);



    }   }
    
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
    public function ShowTransactionByuserId($userId)
    {
        $transaction = Transaction::where('user_id', $userId)->get();
        return response()->json($transaction, 200);
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
    public function ShowTransactionByvehiculeId($vehiculeId)
    {
        $transaction = Transaction::where('vehicule_id', $vehiculeId)->get();
        return response()->json($transaction, 200);
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
    public function ShowTransactionByautoecoleId($ecoleId)
    {
        $transaction = Transaction::where('auto_ecole_id', $ecoleId)->get();
        return response()->json($transaction, 200);
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
    public function update(Request $request,$id){
      $transaction= Transaction::find($id);
       if($transaction){
         $transaction->update($request->all());
         return response()->json($transaction, 200);

       }else {
         $msg = "transaction not found";
         return response()->json($msg, 404);
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
       public function delete($id) {
    // Find the transaction by ID
    $transaction = Transaction::find($id);

    // Check if the transaction exists
    if (!$transaction) {
        $msg = "transaction not found";
        return response()->json($msg, 404);
    }

    // Delete the transaction
    $transaction->delete();

    // Check if the deletion was successful
    if ($transaction->trashed()) {
        return response()->json("transaction deleted successfully", 200);
    } else {
        return response()->json("Failed to delete transaction", 500);
    }
}
}
