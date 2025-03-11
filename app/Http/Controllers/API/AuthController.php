<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
       $validator=Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'role' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => "error",
                "message" => "Validation échouée",
             ],400);
           }
        
       $user = new User();
       
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= Hash::make($request->password);
        $user->role_id= $request->role;
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

        // if(Auth::attempt(['email'=> $request->email, 'password'=> $request->password])){
        // ou
        if(Auth::attempt($request->only('email','password'))){
            $user= Auth::user();
            
            return response()->json([
                "status" => 'success',
                "message" => 'Authentification réussi',
                 'data' => $user,
                 'token' => $user->createToken('myApp')->plainTextToken,
            ],200);
        }
        
        return response()->json([
            "status" => 'error',
            "message" => 'Échec de l"authentification, vérifiez vos informations',
            'data' => NULL
        ],401);
    }

    //methode pour update profile user (request_methode: put)
    public function updateProfile(Request $request){
        $validator=Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => "error",
                "message" => "Validation échouée",
             ],400);
           }
           
        $user= Auth::User();
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password= Hash::make($request->password);
        
        $user->update();
        
        return response()->json([
            "status" => 'success',
            "message" => 'le profile de user a été modifié avec success',
            'data' => $user
        ],200);
    }
}