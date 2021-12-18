<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Goblin;
use Illuminate\Support\Facades\Auth;

class GoblinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request, $message, $status)
    {
        $hash_code = $request->header('X-Goblin-Token');
        $section_id = $request->header('X-Goblin-section-id');
        $goblin = Goblin::where('hash_code', '=', $hash_code)->first();
        if(isset($goblin->hash_code)){
            $goblin->section_id = $section_id;
            $goblin->message = json_encode($message);
            $goblin->status = $status;
            $goblin->user_id = Auth::user()->id;
            $goblin->save();
            unset($goblin->id);
            unset($goblin->section_id);
            unset($goblin->user_id);
            unset($goblin->created_at);
            unset($goblin->updated_at);
            $goblin->message = $message;
            return $goblin;
            
        }else{
            return["status"=>"error", "message"=>"Invalid Goblin Token"];
        }
        
        
        
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($request)
    {
        $hash_code = $request->header('X-Goblin-Token');
        //return $hash_code;
        if(!isset($hash_code)){ return["status"=>"error","message"=>"Please Pass Valid Goblin token"]; }
        $goblin_check = Goblin::select("hash_code","status","message")->where("user_id","=",Auth::user()->id)->where("hash_code","=",$hash_code)->first();
        if(!isset($goblin_check->hash_code)){ 
            return["status"=>"error","message"=>"Please Pass Valid Goblin token"]; 
        }
        if(isset($goblin_check->status)){ 
            $goblin_check->message = json_decode($goblin_check->message);
            return $goblin_check; 
        }else{ return 0; }
    }

    public function generate_goblin($nos)
    {
        
        if(!is_numeric($nos) || $nos > 20 ){ 
            return["status" => "error", "message" => "Please Specify the appropriate number of goblins upto 20"];
        }

        $goblin_check = Goblin::select("hash_code")->where("user_id","=",Auth::user()->id)->where("message","=",null)->get();
        $total_goblin = count($goblin_check);
        if($total_goblin > 10){
            return $goblin_check;
        }

        for ($i = 0; $i <= $nos-1; $i++) {
            $goblin = new Goblin;
            $goblin->hash_code = "U".Auth::user()->id."IT".time()."Z".rand(1000000,999999999)."A".rand(1,999999999);
            $goblin->user_id = Auth::user()->id;
            $goblin->save();
            $goblin_array[] = $goblin;
        }
        return $goblin_array;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
