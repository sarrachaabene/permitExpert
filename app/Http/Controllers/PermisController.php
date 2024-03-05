<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permis; 

class PermisController extends Controller
{
  public function index()
  {
    $permis=Permis::get();
    return response()->json($permis,200);
  }

  public function store(Request $request)
  {
    $permis= Permis::create($request->all());
    if($permis)
    { 
      $permis->save();
      return response()->json($permis, 200);
    }
    return response()->json("permis not created", 400);
   }

   public function show($id){
    $permis = Permis::find($id);
    if($permis){
  return response()->json($permis, 200);

    }else{
      $msg="votre id n'est pas trouve";
          return response()->json($msg, 200);



    }   }
    public function update(Request $request,$id){
      $permis= Permis::find($id);
       if($permis){
         $permis->update($request->all());
         return response()->json($permis, 200);

       }else {
         $msg = "permis not found";
         return response()->json($msg, 404);
     }
       }

       
       public function delete($id) {
        $permis = Permis::find($id);
    
        if (!$permis) {
            $msg = "Permis not found";
            return response()->json($msg, 404);
        }
    
        if ($permis->delete()) {
            return response()->json("Permis deleted successfully", 200);
        } else {
            return response()->json("Failed to delete permis", 500);
        }
    }
    
}
