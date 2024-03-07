<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RessourceEducative;



class RessourceEducativeController extends Controller
{
  public function index()
  {
    $ressourceEducative=RessourceEducative::get();
    return response()->json($ressourceEducative,200);
  }

  public function store(Request $request)
  {
    $ressourceEducative= RessourceEducative::create($request->all());
    if($ressourceEducative)
    { 
      $ressourceEducative->save();
      return response()->json($ressourceEducative, 200);
    }
    return response()->json("ressourceEducative not created", 400);
   }

   public function show($id){
    $ressourceEducative = RessourceEducative::find($id);
    if($ressourceEducative){
  return response()->json($ressourceEducative, 200);

    }else{
      $msg="votre id n'est pas trouve";
          return response()->json($msg, 200);



    }   }
    public function update(Request $request,$id){
      $ressourceEducative= RessourceEducative::find($id);
       if($ressourceEducative){
         $ressourceEducative->update($request->all());
         return response()->json($ressourceEducative, 200);

       }else {
         $msg = "ressourceEducative not found";
         return response()->json($msg, 404);
     }
       }

       
       public function delete($id) {
        $ressourceEducative = RessourceEducative::find($id);
              if (!$ressourceEducative) {
            $msg = "ressourceEducative not found";
            return response()->json($msg, 404);
        }
    
        if ($ressourceEducative->delete()) {
            return response()->json("ressourceEducative deleted successfully", 200);
        } else {
            return response()->json("impossible to delete ressourceEducative", 500);
        }
}
}
