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
 *         property="user_name",
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
     //$token = $user->createToken('My Token')->accessToken;
     //return response()->json(['access_token' => $token]);
    /*  $autoecole = AutoEcole::find($request->auto_ecole_id);
 
     if ($user->role == 'admin') {  
         $user->auto_ecole_id = $autoecole->id; // Assign auto_ecole_id to the new user
         $user->save();
     }*/
 
     return response()->json(["user" => $user], 200); 
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
/**
 * @OA\Post(
 *     path="/api/updateProfile",
 *     summary="Update user profile",
 *     tags={"Profile"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *             @OA\Property(property="password_confirmation", type="string", example="password123"),
 *             @OA\Property(property="numTel", type="string", example="12345678")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *                 @OA\Property(property="numTel", type="string", example="12345678")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=402,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */
public function registerClient(Request $request, $email)
{
    // Trouver l'utilisateur par son email
    $user = User::where('email', $email)->first();

    if ($user) {
        // Si l'utilisateur existe, valider les données envoyées
        $validator = Validator::make($request->all(), [
            'user_name' => [
                'required', 
                'string', 
                'max:255'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'numTel' => ['required', 'string', 'max:8']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 402);
        }

        // Mettre à jour les informations de l'utilisateur
        $user->user_name = $request->user_name;
        $user->password = Hash::make($request->password);
        $user->numTel = $request->numTel;
        $user->save();

        $msg = 'Informations de l\'utilisateur mises à jour avec succès';
        return response()->json(['Message' => $msg, 'user' => $user], 200);
    } else {
        // Si l'utilisateur n'existe pas, renvoyer un message d'erreur
        $error = 'L\'utilisateur avec cet email n\'existe pas';
        return response()->json(['error' => $error], 404);
    }
}

  /**
 * @OA\Get(
 *     path="/api/login",
 *     summary="Login user and generate access token",
 *     tags={"Authentication"},
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="User email",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             format="email",
 *             example="user@example.com"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="password",
 *         in="query",
 *         description="User password",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="password123"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="Message", type="string", example="welcome"),
 *             @OA\Property(property="access_token", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid credentials")
 *         )
 *     ),
 *     @OA\Response(
 *         response=402,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string")
 *         )
 *     )
 * )
 */
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
  
      // If the credentials are valid, generate a token using Passport
      $accessToken = Auth::user()->createToken('authToken')->accessToken;
      $msg="welcome";
      // Return the token as a response
      return response()->json([ 'Message'=>$msg ,'access_token' => $accessToken]);
  }
}