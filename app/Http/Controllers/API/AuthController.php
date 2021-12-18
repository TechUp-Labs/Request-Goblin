<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\OrderDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {
      if(Auth::user()->is_admin){

            if(isset($_GET['query'])){
                $searchquery = $_GET['query'];
                $adminlist = User::select("*")
                                ->where('is_admin', 1)
                                ->where("deleted_at", "=", null)
                                ->orderby("name", "ASC")
                                ->where(function($query) use ($searchquery){
                                    $query->where('first_name','LIKE',"%$searchquery%")
                                    ->orWhere('last_name','LIKE',"%$searchquery%")
                                    ->orWhere('fullname','LIKE',"%$searchquery%")
                                    ->orWhere('email','LIKE',"%$searchquery%")
                                    ->orWhere('mobile','LIKE',"%$searchquery%")
                                    ->orWhere('role','LIKE',"%$searchquery%");
                                })
                                ->paginate(5);
                return $adminlist; 
            }else{
                $adminlist = User::select("*")
                                ->where('is_admin', 1)
                                ->where("deleted_at", "=", null)
                                ->orderby("name", "ASC")
                                ->paginate(5);
                return $adminlist;                                              
            }
       }else{
            return response(['message'=> "You are not authorized to view admins"]);
       }
       
    }

     public function dashboard()
    {
      if(Auth::user()->is_admin){


        

            if(isset($_GET['start_date']) && isset($_GET['end_date'])){

                $from = $_GET['start_date']; //2021-10-07
                $to   = $_GET['end_date'];

                $totalorder = Order::where("deleted_at", "=", null)
                                ->whereBetween('date', [$from, $to])
                                ->count();
                $totalAmount = Order::where("deleted_at", "=", null)
                                ->whereBetween('date', [$from, $to])
                                ->sum('total');
                $totalCardslot = OrderDetails::where("deleted_at", "=", null)
                                ->whereBetween('created_at', [$from, $to])
                                ->sum('quantity');
                
                return response(['total_orders'=> $totalorder,'total_payments'=> $totalAmount,'cards_sold'=> $totalCardslot]);
            }else{

                $totalorder = Order::where("deleted_at", "=", null)
                                ->count();
                $totalAmount = Order::where("deleted_at", "=", null)
                                ->sum('total');
                $totalCardslot = OrderDetails::where("deleted_at", "=", null)
                                ->sum('quantity');
                
                return response(['total_orders'=> $totalorder,'total_payments'=> $totalAmount,'card_slots'=> $totalCardslot]);
                                                         
            }
       }else{
            return response(['message'=> "You are not authorized to view dashboard"]);
       }
       
    }

    public function userlist()
    {
      if(Auth::user()->is_admin){

        if(isset($_GET['query'])){
                $searchquery = $_GET['query'];
                $adminlist = User::select("*")
                                ->where('is_admin', null)
                                ->where("deleted_at", "=", null)
                                ->orderby("name", "ASC")
                                ->where(function($query) use ($searchquery){
                                    $query->where('first_name','LIKE',"%$searchquery%")
                                    ->orWhere('last_name','LIKE',"%$searchquery%")
                                    ->orWhere('fullname','LIKE',"%$searchquery%")
                                    ->orWhere('email','LIKE',"%$searchquery%")
                                    ->orWhere('mobile','LIKE',"%$searchquery%")
                                    ->orWhere('role','LIKE',"%$searchquery%");
                                })
                                ->paginate(5);
                return $adminlist; 
            }else{
            $userlist = User::select("*")
                            ->where('is_admin', null)
                            ->where("deleted_at", "=", null)
                            ->orderby("name", "ASC")
                            ->paginate(5);
            return $userlist; 
            }
       }else{
            return response(['message'=> "You are not authorized to view all users"]);
       }
       
    }

    public function getuserdetail($id)
    {
      if(Auth::user()->is_admin){
            $user = User::find($id);
            $user->profile_img = url('/img/profile')."/".$user->profile_img;
            $user->company_logo = url('img/profile/company')."/".$user->company_logo;
            return $user;
       }else{
            return response(['message'=> "You are not authorized to view admins"]);
       }       
    }

    public function user(){

        if(Auth::user()){
            $authUser = Auth::user();
            if($authUser->deleted_at){
                    $cookie = \Cookie::forget('jwt');
                    return response(['message'=> "Requested User Deleted Please Contact our Support Team"])->withCookie($cookie);
                }
            $authUser->profile_img = url('/img/profile')."/".$authUser->profile_img;
            $authUser->company_logo = url('img/profile/company')."/".$authUser->company_logo;
            return $authUser;
        }else{
            return response(['message'=> 'Unauthenticated User']);
        }

    }

    public function logout(){
        $cookie = \Cookie::forget('jwt');
        //$cookie = \Cookie::forget('*');
        return response(['message'=> 'Logout Successful'])->withCookie($cookie);
    }

    public function forget(Request $request, User $user){
        if($request->input('email')){
            $data = User::where('email', $request->input('email'))->where("deleted_at", "=", null)->first();
        }
        if(!isset($data->id)){return['message'=>'Your Account Does not exist'];}
        if(isset($data->id)){
            $user = User::find($data->id);
            $user->remember_token = rand(100000,999999);
            $from = "zakir@techsingularity.com";
            $to = $request->input('email');
            $subject = "Atlas Forget password code";
            $message = "Please use this code to reset your password :- ".$user->remember_token;
            $message .= "\n URL :- https://jeansatlas.winayak.com/#/reset-password/".$user->remember_token;
            $headers = "From:" . $from;
            mail($to,$subject,$message, $headers);
            $user->save();
        }
        $cookie = \Cookie::forget('jwt');
        return response(['message'=> 'Email Sent Successful'])->withCookie($cookie);
    }

    public function reset(Request $request, User $user){
        if(/* $request->input('email') && */ $request->input('code') && $request->input('newpass')){
            //$data = User::where('email', $request->input('email'))->where('remember_token', $request->input('code'))->where("deleted_at", "=", null)->first();
            $data = User::where('remember_token', $request->input('code'))->where("deleted_at", "=", null)->first();
        }
        if(!isset($data->id)){return['message'=>'Your Account Does not exist or Code Miss Matched'];}
        if(isset($data->id)){
            $user = User::find($data->id);
            $user->password = Hash::make($request->input('newpass'));
            $user->remember_token = NULL;
            $from = "zakir@techsingularity.com";
            $to = $request->input('email');
            $subject = "Atlas Password Reset Successful";
            $message = "Your Atlas password has Been Reset Sucessfully \n Now you can re-login with our new password \n Thankyou! ";
            $headers = "From:" . $from;
            mail($to,$subject,$message, $headers);
            $user->save();
        }
        $cookie = \Cookie::forget('jwt');
        return response(['message'=> 'Password Reset Successful'])->withCookie($cookie);
    }

    public function login(Request $request){
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        

        if($request->input('email') || $request->input('name')){


            if($request->input('email')){
                $data = User::where('email', $request->input('email'))->where("deleted_at", "=", null)->first();
            }

            if($request->input('name')){
                $data = User::where('name', $request->input('name'))->where("deleted_at", "=", null)->first();
            }
            
            if(!isset($data->id)){return['message'=>'Your Account Does not exist'];}

            if(isset($data->id)){

                $authdata = array('name' => $data->name, 'email' => $data->email, 'password' => $request->input('password') );

                if(!Auth::attempt($authdata)){
                return response(['message'=>'Invalid User'], 401);
                }
                $user = Auth::user();
                if($user->deleted_at){
                    $cookie = \Cookie::forget('jwt');
                    return response(['message'=> "Requested User Deleted Please Contact our Support Team"])->withCookie($cookie);
                }
                $token = $user->createToken('token')->plainTextToken;
                if($request->input('remember_me')){
                    $cookie = cookie('jwt', $token, 86000*30 );
                }else{
                    $cookie = cookie('jwt', $token, 60*3*2 );
                }
                return response(['token'=> $token])->withCookie($cookie);
            }
        }


    }

    public function register(Request $request, User $user)
    {
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $data = $request->validate([
                'name'=> 'string|max:100|unique:users',
                'email'=> 'string|required|email|max:100|unique:users',
                'mobile' => 'required|regex:/[0-9]{9}/|unique:users',
                'password' => 'required|string|max:100',
                'dob' => 'date',
                'gender' => 'string|max:15',
                'lang_code' => 'string|max:15',
                'designation' => 'string|max:100'
        ]);

        $user->fullname = $request->input('name');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        if(!$request->input('name')){
            $newname = $request->input('first_name');
        }else{
            $newname = $request->input('name');
        }
        $name = str_replace(" ", "", $newname);
        $name = str_replace(".", "", $name);
        $name = str_replace("_", "", $name);
        $user->name = $name.rand(0,999999).rand(0,999);
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->mobile = $request->input('mobile');
        if($request->input('dob')){$user->dob = $request->input('dob');}
        if($request->input('gender')){$user->gender = $request->input('gender');}
        if($request->input('designation')){$user->designation = $request->input('designation');}
        if($request->input('lang_code')){$user->lang_code = $request->input('lang_code');}else{$user->lang_code = 'EN';}
        
        if($request->input('is_admin') && Auth::user()->is_admin){ $user->is_admin = 1;}        
        if($request->input('role') && Auth::user()->is_admin){ $user->role = $request->input('role');}

        if($request->file("profile_img")){
            $user->profile_img = 'profile_img'.time().'.'.$request->file("profile_img")->extension();
            $request->profile_img->move(public_path('img/profile'), $user->profile_img);
        }

        $user->save();
        return $user;
    }


    public function update(Request $request, $id)
    { 
        if(Auth::user()->is_admin == 1){
            $user = User::find($id);
            if(!isset($user->id)){
                return ['message'=>'User Not Exist'];
            }
        }else{
            $user = Auth::user();
        }
        $currentphoto = $user->profile_img;
        $data = $request->validate([
                'name'=> 'string|max:100|unique:users,name,'.$user->id,
                'email'=> 'string|email|max:100|unique:users,email,'.$user->id,
                'mobile' => 'regex:/[0-9]{9}/|unique:users,mobile,'.$user->id,
                'password' => 'string|max:100',
                'dob' => 'date|nullable',
                'gender' => 'string|max:15|nullable',
                'designation' => 'string|max:100|nullable',
                'lang_code' => 'string|max:15',
                'company_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'profile_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->input('name')){$user->fullname = $request->input('name');}
        if($request->input('first_name')){$user->first_name = $request->input('first_name');}
        if($request->input('last_name')){$user->last_name = $request->input('last_name');}
        if($request->input('email')){$user->email = $request->input('email');}
        if($request->input('mobile')){$user->mobile = $request->input('mobile');}
        if($request->input('password')){$user->password = Hash::make($request->input('password'));}
        if($request->input('designation')){$user->designation = $request->input('designation');}
              
        if($request->input('dob')){$user->dob = $request->input('dob');}
        if($request->input('gender')){$user->gender = $request->input('gender');}
        if($request->input('lang_code')){$user->lang_code = $request->input('lang_code');}
        
        if($request->input('is_admin') && Auth::user()->is_admin){ $user->is_admin = 1;}        
        if($request->input('role') && Auth::user()->is_admin){ $user->role = $request->input('role');}

        if($request->input("company_name")){$user->company_name = $request->input("company_name");}

        if($request->file("company_logo")){
            $currentlogo = public_path('img/profile/company/').$user->company_logo;
            if(file_exists($currentlogo)){ @unlink($currentlogo); }
            $user->company_logo = 'company_logo'.time().'.'.$request->file("company_logo")->extension();
            $request->company_logo->move(public_path('img/profile/company'), $user->company_logo);
        }

        if($request->file("profile_img")){
            $currentlogo = public_path('img/profile/').$user->profile_img;
            if(file_exists($currentlogo)){ @unlink($currentlogo); }
            $user->profile_img = 'profile_img'.time().'.'.$request->file("profile_img")->extension();
            $request->profile_img->move(public_path('img/profile'), $user->profile_img);
        }

        $user->save();
        return ['message'=>'User Updated Sucessfull', $user];
    }

    public function updatepassword(Request $request)
    { 
        $user = Auth::user();
        $data = $request->validate([
                'old_password' => 'string|required|max:100',
                'new_password' => 'string|required|max:100',
                'cnfrm_password' => 'string|required|max:100',
        ]);
        if($request->input('new_password') != $request->input('cnfrm_password')){
            return ['message'=>"Confirm Password Doesn't Match"];
        }
        if($request->input('new_password') == $request->input('old_password')){
            return ['message'=>"New Password Cannot Be Same As Old Password"];
        }   
        if($user && password_verify($request->input('old_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->save();
                return ['message'=>'Password Updated Sucessfully', $user];
        }else{
                return ['message'=>'You Had Entered Incorect Old Password'];
        }
    }

  public function delete($id)
    {
      if(Auth::user()->is_admin){
            $user = User::find($id);
          /*$currentphoto = $user->profile_img;
            $oldProductPhoto = public_path('img/profile/').$currentphoto;
            if(file_exists($oldProductPhoto)){
                @unlink($oldProductPhoto);
            }*/
            $user->deleted_at = date('Y-m-d H:i:s');
            $user->save();
            return ['message'=> "User Deleted Sucessfull"];
       }else{
            return ['message'=> "You are not authorized to delete users"];
       }
       
    }


}
