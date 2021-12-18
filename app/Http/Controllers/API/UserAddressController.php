<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserAddress;
use Illuminate\Support\Facades\Auth;


class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $useraddress = UserAddress::select("*")->where("user_addresses.user_id", "=", Auth::user()->id)->where("user_addresses.deleted_at", "=", null)->get();

        if(!isset($useraddress[0]->id)){
            return ["message" => "No Address Found"];
        }
        
        return $useraddress;


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserAddress $useraddress)
    {
        $data = $request->validate([
                'type' => 'string|required|max:100',
                'address_line_1' => 'string|required|max:100',
                'address_line_2' => 'string|required|max:100',
                'area' => 'string|required|max:100',
                'city' => 'string|required|max:100',
                'state' => 'string|required|max:100',
                'country' => 'string|required|max:100',
                'zip' => 'string|required|max:100',
                'mobile' => 'regex:/[0-9]{9}/|unique:user_addresses',
        ]);

        $useraddress = UserAddress::select("*")->where("user_addresses.user_id", "=", Auth::user()->id)->where("user_addresses.type", "=", $request->input('type'))->where("user_addresses.deleted_at", "=", null)->first();

        if(!isset($useraddress->id)){
            $useraddress = new UserAddress;
            $useraddress->user_id = Auth::user()->id;
            $useraddress->type = $request->input('type');
            $useraddress->address_line_1 = $request->input('address_line_1');
            $useraddress->address_line_2 = $request->input('address_line_2');
            $useraddress->area = $request->input('area');
            $useraddress->city = $request->input('city');
            $useraddress->state = $request->input('state');
            $useraddress->country = $request->input('country');
            $useraddress->zip = $request->input('zip');
            if($request->input('mobile')){$useraddress->mobile = $request->input('mobile');}
        }else{
            $useraddress = UserAddress::find($useraddress->id);
            if($request->input('type')){$useraddress->type = $request->input('type');}
            if($request->input('address_line_1')){$useraddress->address_line_1 = $request->input('address_line_1');}
            if($request->input('address_line_2')){$useraddress->address_line_2 = $request->input('address_line_2');}
            if($request->input('area')){$useraddress->area = $request->input('area');}
            if($request->input('city')){$useraddress->city = $request->input('city');}
            if($request->input('state')){$useraddress->state = $request->input('state');}
            if($request->input('country')){$useraddress->country = $request->input('country');}
            if($request->input('zip')){$useraddress->zip = $request->input('zip');}
            if($request->input('mobile')){$useraddress->mobile = $request->input('mobile');}
        }     

        $useraddress->save();

        return $useraddress;

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!isset(Auth::user()->is_admin)){
            $useraddress = UserAddress::select("*")->where("user_addresses.user_id", "=", Auth::user()->id)->where("user_addresses.deleted_at", "=", null)->where("user_addresses.id", "=", $id)->first();
        }else{
            $useraddress = UserAddress::select("*")->where("user_addresses.deleted_at", "=", null)->where("user_addresses.id", "=", $id)->first();
        }
        

        if(!isset($useraddress->id)){
            return ["message" => "No Address Found"];
        }

        return $useraddress;
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