<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seance;

class SeanceController extends Controller
{
    public function index()
    {
      $seance=Seance::get();
      return response()->json($seance,200);
    }

    public function store(Request $request)
    {
      $seance= Seance::create($request->all());
      if($seance)
      { 
        $seance->save();
        return response()->json($seance, 200);
      }
      return response()->json("seance not created", 400);
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
}