<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(
 *         property="name",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="cin",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="prenom",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="numTel",
 *         type="string"
 *     ),
 *     @OA\Property(
 *         property="dateNaissance",
 *         type="string"
 *     ),
 * )
 */


class ApiController extends Controller {
  /**
 * @OA\Get(
 *      path="/api/user/index",
 *      operationId="getUserList",
 *      tags={"Users"},
 *      summary="Get list of Users",
 *      description="Returns list of Users",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/User")
 *          ),
 *      ),
 * )
 */
  public function index(){
    $user= User::get();
  return response()->json($user, 200);
  }
/**
 * @OA\Post(
 *      path="/api/user/store",
 *      operationId="createUser",
 *      tags={"Users"},
 *      summary="Create a new User",
 *      description="Create a new User",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *      )
 * )
 */

 public function store(Request $request)
 {
     $user = User::create($request->all());
     $token = $user->createToken('My Token')->accessToken;
     return response()->json(['access_token' => $token]);
    /*  $autoecole = AutoEcole::find($request->auto_ecole_id);
 
     if ($user->role == 'admin') {  
         $user->auto_ecole_id = $autoecole->id; // Assign auto_ecole_id to the new user
         $user->save();
     }
 
     return response()->json(["user" => $user, "token" => $token], 200); */
 }
/**
 * @OA\Get(
 *      path="/api/user/show/{id}",
 *      operationId="getUserById",
 *      tags={"Users"},
 *      summary="Get a User by ID",
 *      description="Returns a single User",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the user to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found"
 *      )
 * )
 */


     public function show($id){
      $user = User::find($id);
      if($user){
    return response()->json($user, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }

      /**
 * @OA\Put(
 *      path="/api/user/update/{id}",
 *      operationId="updateUser",
 *      tags={"Users"},
 *      summary="Update an existing User",
 *      description="Updates an existing User",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the user to update",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found"
 *      )
 * )
 */
      public function update(Request $request,$id){
       $user= User::find($id);
        if($user){
          $user->update($request->all());
          return response()->json($user, 200);

        }else {
          $msg = "User not found";
          return response()->json($msg, 404);
      }
        }
/**
 * @OA\Delete(
 *      path="/api/user/delete/{id}",
 *      operationId="deleteUser",
 *      tags={"Users"},
 *      summary="Delete a User",
 *      description="Delete a user by ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the user to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User data",
 *          @OA\JsonContent(ref="#/components/schemas/User")
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="User not found"
 *      )
 * )
 */
public function delete($id) {
    // Find the user by ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        $msg = "User not found";
        return response()->json($msg, 404);
    }
    return response()->json($user, 200);
    // Delete the user
    //$user->delete();

    // Check if the deletion was successful
    /* if ($user->trashed()) {
        return response()->json("User deleted successfully", 200);
    } else {
        return response()->json("Failed to delete user", 500);
    } */
}
  public function registerClient(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => ['required', 'string', 'email', 'max:255', Rule::exists('users', 'email')],
      'password' => ['required', 'string', 'min:8','confirmed'],
      'numTel' => ['required', 'string', 'max:8']
  ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 402);
    }
     // Create or update the user
     $user = User::updateOrCreate(
      ['email' => $request->email], // Find user by email
      ['password' => Hash::make($request->password), // Hash the password
       'numTel' => $request->numTel, // Update the phone number
       'name' => $request->name // Update the phone number
      ]
  );
  // You can return a success response if needed
  return response()->json(['user' => $user], 200);
  }
  
  public function loginClient(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'email' => ['required', 'string', 'email', 'max:255', Rule::exists('users', 'email')],
          'password' => ['required', 'string', 'min:8']
      ]);
      if ($validator->fails()) {
          return response()->json($validator->errors(), 402);
      }
       // Attempt to authenticate the user
    $credentials = $request->only('email', 'password');
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
      // Generate a token for the authenticated user
  //    $accessToken = Auth::user()->createToken('authToken')->accessToken;
      return  Auth::user()->createToken("habib")
             ->plainTextToken;

      // You can add additional payload data here
  /*     $payload = [
          'user' => Auth::user(), // Add user data to the payload if needed
          'access_token' => $accessToken,
      ]; */
  
      // Return the payload and token
    /*   return response()->json($payload, 200); */
  }
}