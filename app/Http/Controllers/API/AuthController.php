<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);
        
       $user = new User();
       
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= Hash::make($request->password);
        $user->save();
        
    
        
        return response()->json([
            "status" => 'success',
            "message" => 'Utilisateur enregistré avec succès',
            'data' => [
                'token'=> $user->createToken('myApp')->plainTextToken,
                'name' => $user->name,
                'email'=> $user->email,
            ],
        ],201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required'
        ]);

        // if(Auth::attempt(['email'=> $request->email,'password'=> $request->password])){
        // ou
        if(Auth::attempt($request->only('email','password'))){
            $user= Auth::user();
            
            return response()->json([
                "status" => 'success',
                "message" => 'Utilisateur enregistré avec succès',
                 'data' => $user
            ]);
        }
        
        return response()->json([
            "status" => 'error',
            "message" => 'errpr authentification',
            'data' => NULL
        ]);
    }
}