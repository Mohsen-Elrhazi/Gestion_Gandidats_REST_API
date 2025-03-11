<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "status" => "success",
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

       $validator= Validator::make($request->all(),[
            'title' =>'required',
            'description' =>'required',
            'location' =>'required',
            'contract_type' =>'required',
       ]);

       if($validator->fails()){
        return response()->json([
            "status" => "error",
            "message" => "Veuillez remplir tous les champs",
         ]);
       }
       
         $user= Auth::user();
        //  $user= auth()->user();
        $offre= new Offre();
        
        $offre->title=$request->title;
        $offre->description=$request->description;
        $offre->location=$request->location;
        $offre->contract_type=$request->contract_type;
        $offre->user_id= $user->id;
        
         $offre->save();
         
         return response()->json([
            "status" => "success",
            "message" => "offre a ete enregistre",
            "data" => $offre,
         ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Offre $offre)
    {
        return response()->json([
            "status" => "uccesss",
            "data" => $offre,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offre $offre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offre $offre)
    {
        $validator= Validator::make($request->all(),[
            'title' =>'required',
            'description' =>'required',
            'location' =>'required',
            'contract_type' =>'required',
       ]);

       if($validator->fails()){
        return response()->json([
            "status" => "error",
            "message" => "Veuillez remplir tous les champs",
         ],400);
       }
// $offre=Offre::find($id);
       $offre->title=$request->title;
       $offre->description=$request->description;
       $offre->location=$request->location;
      $offre->contract_type=$request->contract_type;

      $offre->update();
    
      return response()->json([
        "status" => "success",
        "message" => "offre a ete modifie",
     ],200);
   }
        
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offre $offre)
    {
        $offre->delete();
        
        return response()->json([
            "status" => "success",
            "message" => "offre a ete supprimé",
         ],200);
    }
}