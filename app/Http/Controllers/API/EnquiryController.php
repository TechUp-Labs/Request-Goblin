<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enquiry;
use Illuminate\Support\Facades\Auth;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->is_admin){
            $enquiry = Enquiry::select("*")->where("enquiries.user_id", "=", Auth::user()->id)->where("enquiries.deleted_at", "=", null)->get();
            return $enquiry;
        }else{
            $enquiry = Enquiry::select("*")->where("enquiries.deleted_at", "=", null)->paginate(30);
            return $enquiry;
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Enquiry $enquiry)
    {
        $data = $request->validate([
                'first_name' => 'string|required|max:100',
                'last_name' => 'string|required|max:100',
                'company' => 'string|max:100',
                'email' => 'email|required|max:100',
                'phone' => 'required|regex:/[0-9]{9}/',
                'message' => 'string|required|max:1000'
        ]);

        if(!Auth::user()->id){
            return["message" => "You Are Not Logged In You Can't Post Close Enquiry Please Use Diffrent EndPoint"];
        } 

        if(Auth::user()->id){
            $enquiry->user_id = Auth::user()->id;
        }        
        $enquiry->first_name = $request->input('first_name');
        $enquiry->last_name = $request->input('last_name');
        $enquiry->company = $request->input('company');
        $enquiry->email = $request->input('email');
        $enquiry->phone = $request->input('phone');
        $enquiry->message = $request->input('message');

        $enquiry->save();

        return $enquiry;
    }

    public function openenquiry(Request $request, Enquiry $enquiry)
    {
        $data = $request->validate([
                'first_name' => 'string|required|max:100',
                'last_name' => 'string|required|max:100',
                'company' => 'string|max:100',
                'email' => 'email|required|max:100',
                'phone' => 'required|regex:/[0-9]{9}/',
                'message' => 'string|required|max:1000'
        ]);

        $enquiry->user_id = 0;                
        $enquiry->first_name = $request->input('first_name');
        $enquiry->last_name = $request->input('last_name');
        $enquiry->company = $request->input('company');
        $enquiry->email = $request->input('email');
        $enquiry->phone = $request->input('phone');
        $enquiry->message = $request->input('message');

        $enquiry->save();
        return $enquiry;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
