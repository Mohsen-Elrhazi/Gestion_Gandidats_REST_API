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
        ],[
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'confirm_password.required' => 'La confirmation du mot de passe est requise.',
            'confirm_password.same' => 'Les mots de passe ne correspondent pas.',
            'role.required' => 'Le rôle est obligatoire.',
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => "error",
                "message" => "Validation échouée",
                "errors"=> $validator->errors()
             ],400);
           }
           
        try{
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
    }catch(\Exception $e){
        return response()->json([
            'status'=> 'error',
            'message'=> $e->getMessage(),
        ],401);
    }
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
            'name' => 'sometimes|required',
            'email' => 'sometimes|required|email||unique:users,email,' . Auth::id(),
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => "error",
                "message" => "Validation échouée",
             ],400);
           }
           
        $user= Auth::User();

        if(Hash::check($request->old_password,$user->password)){
            $user->name= $request->name;
            $user->email= $request->email;
            $user->password= Hash::make($request->new_password);

            $user->update();
        
            return response()->json([
                "status" => 'success',
                "message" => 'le profile de user a été modifié avec success',
                'data' => $user
            ],200);
            
         }else{
            return response()->json([
                "status" => 'error',
                "message" => "L'ancien mot de passe est incorrect",
            ],400);
         }    
    }

    
}