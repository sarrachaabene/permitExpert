<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class ApiContoller extends Controller
{
    public function store(Request $request)
    {
      $user=User::create($request->all());
      if($user)
      {
        return response()->json($user, 200);
      }
      return response()->json("user not created", 400);
     }
}
