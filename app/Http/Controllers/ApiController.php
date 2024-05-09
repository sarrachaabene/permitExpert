<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AutoEcole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Notifications\VerificationCodeNotification;

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
//Affichage pour admin
  public function index()
  {
      try {
          $roles = ['candidat', 'moniteur', 'secretaire'];
                    $users = User::whereIn('role', $roles)->get();
                    if ($users->isEmpty()) {
              return response()->json("Aucun utilisateur trouvé pour les rôles spécifiés", 404);
          }
          return response()->json($users, 200);
      } catch (\Exception $e) {
          return response()->json("Erreur lors de la récupération des utilisateurs: " . $e->getMessage(), 500);
      }
  }
  
  //afficher le nombre des utilisateurs
  public function CountUser()
  {
      try {
          $roles = ['candidat', 'moniteur', 'secretaire'];
          $users = User::whereIn('role', $roles)->get();
  
          if ($users->isEmpty()) {
              return response()->json("Aucun utilisateur trouvé pour les rôles spécifiés", 404);
          }
  
          $countByRole = [
              'candidat' => 0,
              'moniteur' => 0,
              'secretaire' => 0,
          ];
            foreach ($users as $user) {
              $countByRole[$user->role]++;
          }
  
          return response()->json([
              'candidats' => $countByRole['candidat'],
              'moniteurs' => $countByRole['moniteur'],
              'secretaires' => $countByRole['secretaire']
          ], 200);
      } catch (\Exception $e) {
          return response()->json("Erreur lors de la récupération des utilisateurs: " . $e->getMessage(), 500);
      }
  }


//Affichage pour superAdmin
  public function indexForSuper()
  {
      try {
          $users = User::where('role', 'admin')->get();
                    if ($users->isEmpty()) {
              return response()->json("Aucun utilisateur trouvé avec le rôle 'admin'", 404);
          }
                    return response()->json($users, 200);
      } catch (\Exception $e) {
          return response()->json("Erreur lors de la récupération des utilisateurs: " . $e->getMessage(), 500);
      }
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

 //store pour admin 
 public function store(Request $request)
 {
     try {
         $validatedData = $request->validate([
             'user_name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'string|min:8',
             'role' => 'required|string|in:candidat,moniteur,secretaire',
             'cin' => 'required|string|max:255',
             'numTel' => 'required|string|max:255',
             'dateNaissance' => 'required|date',
             'cat_permis' => ($request->role === 'candidat') ? 'required|string|in:Permis_A1,Permis_A,Permis_B,Permis_B_E,Permis_C,Permis_C_E,Permis_D,Permis_D_E,Permis_D1,Permis_H' : '', // Validation conditionnelle pour cat_permis
         ]);
          $user = User::create($validatedData);
          $defaultRole = $validatedData['role']; 
         $user->assignRole($defaultRole);
          if ($defaultRole === 'candidat') {
             $user->cat_permis = $validatedData['cat_permis'];
             $user->save();
         }
          return response()->json(["user" => $user], 200); 
     } catch (\Exception $e) {
         return response()->json(["error" => "Failed to create user: " . $e->getMessage()], 500);
     }
 }
//store pour SuperAdmin
public function storeForSuperAdmin(Request $request)
{
    try {
        $validatedData = $request->validate([
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'string|min:8',
            'role' => 'required|string|in:admin',
            'cin' => 'required|string|max:255',
            'numTel' => 'required|string|max:255',
            'dateNaissance' => 'required|date',
        ]);
        $user = User::create($validatedData);
        $defaultRole = $validatedData['role']; 
        $user->assignRole($defaultRole);
        return response()->json(["user" => $user], 200); 
    } catch (\Exception $e) {
        return response()->json(["error" => "Failed to create user: " . $e->getMessage()], 500);
    }
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
//show for admin
public function show($id)
{
    try {
        $user = User::find($id);
        if (!$user) {
            $msg = "L'utilisateur avec l'ID spécifié n'a pas été trouvé";
            return response()->json($msg, 404);
        }
        if ($user->role !== 'secretaire' && $user->role !== 'candidat' && $user->role !== 'moniteur') {
            return response()->json(["error" => "Accès non autorisé pour cet utilisateur"], 403);
        }
        return response()->json($user, 200);
    } catch (\Exception $e) {
        return response()->json(["error" => "Failed to fetch user: " . $e->getMessage()], 500);
    }
}
 //show for SuperAdmin
 public function showForSuperAdmin($id)
 {
     try {
         $user = User::find($id);
         if (!$user) {
             $msg = "L'utilisateur avec l'ID spécifié n'a pas été trouvé";
             return response()->json($msg, 404);
         }
         if ($user->role !== 'admin' ) {
             return response()->json(["error" => "Accès non autorisé pour cet utilisateur"], 403);
         }
         return response()->json($user, 200);
     } catch (\Exception $e) {
         return response()->json(["error" => "Failed to fetch user: " . $e->getMessage()], 500);
     }
 }
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
//update for admin
public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'user_name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:8',
            'role' => 'string|in:candidat,moniteur,secretaire',
            'cin' => 'string|max:255',
            'numTel' => 'string|max:255',
            'dateNaissance' => 'date',
            'cat_permis' => ($request->role === 'candidat') ? 'required|string|in:Permis_A1,Permis_A,Permis_B,Permis_B_E,Permis_C,Permis_C_E,Permis_D,Permis_D_E,Permis_D1,Permis_H' : 'nullable', // Validation conditionnelle pour cat_permis
        ]);

        $user = User::find($id);
        if (!$user) {
            $msg = "L'utilisateur avec l'ID spécifié n'a pas été trouvé";
            return response()->json($msg, 404);
        }
        if (!in_array($user->role, ['candidat', 'moniteur', 'secretaire'])) {
            return response()->json(["error" => "Accès non autorisé pour la mise à jour de cet utilisateur"], 403);
        }
        if ($user->role === 'candidat') {
            if (!isset($validatedData['cat_permis'])) {
                return response()->json(["error" => "Le champ 'cat_permis' est obligatoire pour le rôle 'candidat'"], 422);
            }
        }
        $user->update($validatedData);
        return response()->json($user, 200);
    } catch (\Exception $e) {
        return response()->json(["error" => "Failed to update user: " . $e->getMessage()], 500);
    }
}
//update dor SuperAdmin
public function updateForSuperAdmin(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'user_name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:8',
            'role' => 'string|in:admin,candidat,moniteur,secretaire',
            'cin' => 'string|max:255',
            'numTel' => 'string|max:255',
            'dateNaissance' => 'date',
            'cat_permis' => ($request->role === 'candidat') ? 'required|string|in:Permis_A1,Permis_A,Permis_B,Permis_B_E,Permis_C,Permis_C_E,Permis_D,Permis_D_E,Permis_D1,Permis_H' : 'nullable', // Validation conditionnelle pour cat_permis
        ]);
        $user = User::find($id);
        if (!$user) {
            $msg = "L'utilisateur avec l'ID spécifié n'a pas été trouvé";
            return response()->json($msg, 404);
        }
        if ($user->role !== 'admin') {
            return response()->json(["error" => "Accès non autorisé pour la mise à jour de cet utilisateur"], 403);
        }
        $user->update($validatedData);
        return response()->json($user, 200);
    } catch (\Exception $e) {
        return response()->json(["error" => "Failed to update user: " . $e->getMessage()], 500);
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

     // TODO: Test sur le role , utiliser
     //delete for admin
     public function delete($id) {
      $user = User::find($id);
      if (!$user) {
          $msg = "User not found";
          return response()->json($msg, 404);
      }
            if ($user->role !== 'candidat' && $user->role !== 'moniteur' && $user->role !== 'secretaire') {
          return response()->json("Accès non autorisé pour supprimer cet utilisateur", 403);
      }
      try {
          $user->delete();
      } catch (\Exception $e) {
          return response()->json("Failed to delete user. Error: " . $e->getMessage(), 500);
      }
      if ($user->trashed()) {
          return response()->json("User deleted successfully", 200);
      } else {
          return response()->json("Failed to delete user", 500);
      }
  }
  //delete for superAdmin
  public function deleteForSuperAdmin($id) {
    $user = User::find($id);
    if (!$user) {
        $msg = "User not found";
        return response()->json($msg, 404);
    }
          if ($user->role !== 'admin') {
        return response()->json("Accès non autorisé pour supprimer cet utilisateur", 403);
    }
    try {
        $user->delete();
    } catch (\Exception $e) {
        return response()->json("Failed to delete user. Error: " . $e->getMessage(), 500);
    }
    if ($user->trashed()) {
        return response()->json("User deleted successfully", 200);
    } else {
        return response()->json("Failed to delete user", 500);
    }
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

 public function registerClient($email)
 {
     $user = User::where('email', $email)->first();
     if ($user) {
         $validator = Validator::make(request()->all(), [
             'user_name' => ['required', 'string', 'max:255'],
             'password' => ['required', 'string', 'min:8', 'confirmed'],
             'numTel' => ['required', 'string', 'max:8']
         ]);
         if ($validator->fails()) {
             return response()->json($validator->errors(), 402);
         }
          $user->user_name = request()->user_name;
         $user->password = Hash::make(request()->password);
         $user->numTel = request()->numTel;
         $user->save();
 
         $msg = 'Informations de l\'utilisateur mises à jour avec succès';
         return response()->json(['Message' => $msg, 'user' => $user], 200);
     } else {
         $error = 'L\'utilisateur avec cet email n\'existe pas';
         return response()->json(['error' => $error], 404);
     }
 }

 public function updatePassword($email)
 {
     $user = User::where('email', $email)->first();
      if ($user) {
         $validator = Validator::make(request()->all(), [
             'password' => ['required', 'string', 'min:8', 'confirmed'],
         ]);
          if ($validator->fails()) {
             return response()->json($validator->errors(), 402);
         }
          if (empty(request()->password)) {
             $error = 'Le champ de mot de passe ne peut pas être vide';
             return response()->json(['error' => $error], 400);
         }
          $user->password = Hash::make(request()->password);
         $user->save();
          $msg = 'Mot de passe mis à jour avec succès';
         return response()->json(['message' => $msg], 200);
     } else {
         $error = "L'utilisateur avec cet e-mail n'existe pas";
         return response()->json(['error' => $error], 404);
     }
 }
 


 public function showProfile()
 {
   try {
     if (!Auth::check()) {
       return response()->json(["error" => "Utilisateur non authentifié."], 401);
     } 
     $userId = Auth::id();
     $user = User::where('id', $userId)->first(); 
     if (!$user) {
       return response()->json(["error" => "Utilisateur introuvable."], 404); 
     }
      $userData = [
       "user_name" => $user->user_name,
       "dateNaissance" => $user->dateNaissance,
       "numTel" => $user->numTel,
       "email" => $user->email
     ];
     return response()->json($userData, 200);
   } catch (\Exception $e) {
     return response()->json(["error" => "Une erreur s'est produite lors de la récupération des détails de l'utilisateur."], 500);
   }
 }

 public function updateProfile()
{
    try {
        if (!Auth::check()) {
            return response()->json(["error" => "Utilisateur non authentifié."], 401);
        }

        $userId = Auth::id();
        $user = User::findOrFail($userId);

        $validator = Validator::make(request()->all(), [
          'user_name' => 'required|string|max:255',
          'dateNaissance' => 'required|date',
          'numTel' => 'required|string|max:20',
          'email' => 'required|email|max:255',
      ]);
      

        if ($validator->fails()) {
            return response()->json(["error" => "Données de mise à jour invalides.", "errors" => $validator->errors()], 400);
        }

        $user->fill(request()->only(['user_name', 'dateNaissance', 'numTel', 'email']));
        $user->save();

        return response()->json(["message" => "Informations de l'utilisateur mises à jour avec succès."], 200);
    } catch (\Exception $e) {
        return response()->json(["error" => "Une erreur s'est produite lors de la mise à jour des détails de l'utilisateur."], 500);
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
        $credentials = $request->only('email', 'password');
      if (!Auth::attempt($credentials)) {
          return response()->json(['message' => 'Invalid credentials'], 401);
      }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
      
      $msg="welcome";
      return response()->json([ 'Message'=>$msg ,
                                'access_token' => $accessToken,
                                'email'=>Auth::user()->email,
                                'role'=>Auth::user()->role]);
  }


  
  public function checkEmail($email)
  {
      $user = User::where('email', $email)->first();
      if (!$user) {
          return response()->json(['error' => 'Cet email n\'existe pas.'], 404);
      }
      if($user->password==null){
      $verificationCode = rand(1000, 9999); 
      $user->verification_code = $verificationCode;
      $user->save();
      $user->notify(new VerificationCodeNotification($verificationCode));
      return response()->json(['success' => 'Le code de vérification a été envoyé à votre email.']);
           }
      return response()->json(['error' => 'you have aslo password']);  
  }


  public function verifyCode($email, $code)
  {
      if (empty($email) || empty($code)) {
          return response()->json(['error' => 'Les paramètres sont vides.'], 400);
      }      $user = User::where('email', $email)->first();
      if (!$user) {
          return response()->json(['error' => 'Cet email n\'existe pas.'], 404);
      }      
      if ($user->verification_code !== $code) {
          return response()->json(['error' => 'Le code de vérification est incorrect.'], 400);
      }  
      return response()->json(['success' => 'Le code de vérification est correct.']);
  }
  


    public function checkEmailForPassword($email)
  {
      $user = User::where('email', $email)->first();
      if (!$user) {
          return response()->json(['error' => 'Cet email n\'existe pas.'], 404);
      }
      $verificationCode = rand(1000, 9999); 
      $user->verification_code = $verificationCode;
      $user->save();
      $user->notify(new VerificationCodeNotification($verificationCode));
      return response()->json(['success' => 'Le code de vérification a été envoyé à votre email.']);
             }
  
/* // Vérifier le code de vérification
public function verifyCode(Request $request)
{
    $code = $request->input('code');
    $user = Auth::user();
    if ($user->verification_code == $code) {
        // Code de vérification correct, enregistrez l'utilisateur
        $user->verification_code = null;
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Inscription réussie.');
    }
    return redirect()->back()->with('error', 'Code de vérification incorrect.');
} */
  
}
