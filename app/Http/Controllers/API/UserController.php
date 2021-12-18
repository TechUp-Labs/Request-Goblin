<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\SalonService;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return User::latest()->paginate(5);
        //return auth()->user();
        $data = User::select('*')->where("type", "=", 3)->where("under_verification", "=", NULL)->where("is_active", "=", 1)->paginate(20);
        return $data;
    }

    public function monthartist()
    {
        //return User::latest()->paginate(5);
        //return auth()->user();
        $data = User::select('*')->where("type", "=", 3)->where("under_verification", "=", NULL)->where("month_artist", "=", 1)->paginate(20);
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        //return $request->all();

        /*
        $data = request()->validate([
                'name'=> 'required|string|max:30',
                'type'=> 'required|string|max:10',
                'email'=> 'required|string|email|max:150|unique:users',
                'password'=> 'required|string|min:6'
        ]);


        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->bio = $request->input("bio");
        $user->type = $request->input("type");


        $user->account_no = $request->input("account_no");
        $user->address = $request->input("address");
        $user->city = $request->input("city");
        $user->country_code = $request->input("country_code");
        $user->fullname = $request->input("fullname");
        $user->ifsc_code = $request->input("ifsc_code");
        $user->mobile = $request->input("mobile");
        $user->photo = $request->file("photo");
        $user->pin = $request->input("pin");
        $user->state_code = $request->input("state_code");
            if ($request->file("photo")) {
                //$ext = explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';') ))[1] )[1];
                $ext = $request->file("photo")->extension();
                $file = $request->input("name").'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("photo"))->save(public_path('img/profile/').$file);

                //$user->photo = public_path('img/profile/').$user->photo;
                $user->photo = $file;

            }
        $user->address = $request->address;



        $user->save();

       */
        //return['message'=>'Saved'];
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = auth()->user();
        if($user->referral_code == NULL || $user->referral_code == 'NULL') {
          $user->referral_code = $this->random_strings(8);
          $user->save();
        }
        return $user;
    }

    public function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
                          0, $length_of_string);
    }

    public function updateprofile(Request $request,  User $user)
    {
       // return auth('api')->user();
        $user = auth()->user();
        $data = request()->validate([
                'account_no'=> 'string|max:150|unique:users,account_no,'.$user->id,
        ]);

        $user->bio = $request->input("bio");
        $user->type = $request->input("type");

        $user->account_no = $request->input("account_no");
        $user->bank_name = $request->input("bank_name");
        $user->account_type = $request->input("account_type");
        $user->address = $request->input("address");
        $user->city = $request->input("city");
        $user->country_code = $request->input("country_code");
        $user->fullname = $request->input("fullname");
        $user->ifsc_code = $request->input("ifsc_code");
        $user->photo = $request->file("photo");
        $user->pin = $request->input("pin");
        $user->state_code = $request->input("state_code");
        $user->type = $request->input("type");
        $user->years_of_experience = $request->input("years_of_experience");
        $user->pan_number = $request->input("pan_number");

        if($request->input("referral_code")) {
          $referralUser = DB::table('users')->where('referral_code', '=', $request->input("referral_code"))->first();
          if($referralUser) {
            $user->referral_user_id = $referralUser->id;
          }
        }

        if(!empty($request->input("password"))){
            $user->password = Hash::make($request->input("password"));
        }
        $currentphoto = $user->photo;
        if ($request->file("photo")) {

            $ext = $request->file("photo")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = $request->input("user_name").'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("photo"))->save(public_path('img/profile/').$file);

                //$user->photo = public_path('img/user/').$user->photo;
                $user->photo = $file;
                $user->under_verification = 1;
                $olduserPhoto = public_path('img/profile/').$currentphoto;
                if(file_exists($olduserPhoto)){
                    @unlink($olduserPhoto);
                }

                }else {
                    return['message'=>'Due to Security Issue we only accept Image Extention of jpg or png only'];
                    exit();
                }
        }
        $user->save();
        return auth()->user();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $user = auth()->user();

        $data = request()->validate([
                'account_no'=> 'string|max:100|unique:users,account_no,'.$user->id,
                'address'=> 'string|max:100',
                'city'=> 'string|max:100',
                'country_code'=> 'string|max:100',
                'fullname'=> 'string|max:100',
                'company_name'=> 'string|max:100',
                'ifsc_code'=> 'string|max:15',
                'pin'=> 'string|max:15',
                'state_code'=> 'string|max:50',
                'company_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if($request->input("bio")){$user->bio = $request->input("bio");}
        if($request->input("account_no")){$user->account_no = $request->input("account_no");}
        if($request->input("bank_name")){$user->bank_name = $request->input("bank_name");}
        if($request->input("account_type")){$user->account_type = $request->input("account_type");}
        if($request->input("lat")){$user->lat = $request->input("lat");}
        if($request->input("lng")){$user->lng = $request->input("lng");}
        if($request->input("address")){$user->address = $request->input("address");}
        if($request->input("city")){$user->city = $request->input("city");}
        if($request->input("country_code")){$user->country_code = $request->input("country_code");}
        if($request->input("fullname")){$user->fullname = $request->input("fullname");}
        if($request->input("ifsc_code")){$user->ifsc_code = $request->input("ifsc_code");}
        if($request->input("pin")){$user->pin = $request->input("pin");}
        if($request->input("company_name")){$user->company_name = $request->input("company_name");}
        if($request->file("company_logo")){
            $currentlogo = public_path('img/profile/company/').$user->company_logo;
            if(file_exists($currentlogo)){ @unlink($currentlogo); }
            $user->company_logo = 'company-logo'.time().'.'.$request->file("company_logo")->extension();
            $request->image->move(public_path('img/profile/company'), $user->company_logo);
        }
        if($request->input("state_code")){$user->state_code = $request->input("state_code");}
        if($request->input("pan_number")){$user->pan_number = $request->input("pan_number");}
        if($request->input("years_of_experience")){$user->years_of_experience = $request->input("years_of_experience");}

        if($request->input("referral_code") && $request->input("referral_code") != "") {
          $referralUser = User::whereRaw("BINARY `referral_code`= ?", [$request->input("referral_code")])->first();
          if($referralUser) {
            $user->referral_user_id = $referralUser->id;
          }
        }

        $currentphoto = $user->photo;

        if ($request->file("photo") != $currentphoto && $request->file("photo")) {

            $ext = $request->file("photo")->extension();

            if($ext === 'jpg' || $ext === 'png'){

                $file = $user->name.'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("photo"))->save(public_path('img/profile/').$file);

                //$user->photo = public_path('img/user/').$user->photo;
                $user->photo = $file;
                $olduserPhoto = public_path('img/profile/').$currentphoto;
                if(file_exists($olduserPhoto)){
                    @unlink($olduserPhoto);
                }

                }else {
                    return['message'=>'Due to Security Issue we only accept Image Extention of jpg or png only'];
                    exit();
                }
        }

        if(!empty($request->input("password"))){
            $user->password = Hash::make($request->input("password"));
        }
        //$user->type = $request->type;
        $user->save();

        return $user;
    }


     /**
     * Check the referral code in the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $referral_code
     * @return \Illuminate\Http\Response
     */
    public function checkReferralCode(Request $request)
    {

        $code = $request->code;

        $referralUser = User::whereRaw("BINARY `referral_code`= ?", [$code])->first();

        if($referralUser != []) {
          $message = ['message'=> "Referral Code Matched"];
        } else {
          $message = ['message'=> "Invalid Referral Code"];
        }

        return  $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        $currentid = auth()->user()->id;

        $currentpwd = $user->makeVisible(['password']);
        $currentpwd = User::select("password")
                        ->where("id", "=", $currentid)
                        ->get();

       // print_r($currentpwd[0]->password);

        if (password_verify($request->input("password"), $currentpwd[0]->password)) {

            $user = User::find($currentid);
            $user->delete();
            //echo "Success";
            return['message'=>'Success'];
        }else{
            return['message'=>'Failed'];
        }

    }

    public function findUser()
    {
       // $user = User::findOrFail($id);
        if($search = \Request::get('q')){
            $users = User::where(function($query) use ($search){
                    $query->where('name','LIKE',"%$search%")
                          ->orWhere('fullname','LIKE',"%$search%")
                          ->orWhere('type','LIKE',"%$search%")
                          ->orWhere('bio','LIKE',"%$search%")
                          ->orWhere('mobile','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }

        return $users;

    }

    public function uploadProfileImage(Request $request) {

      $user = User::find($request->id);

      $currentphoto = $user->photo;

      if ($request->file("photo") != $currentphoto && $request->file("photo")) {

          $ext = $request->file("photo")->extension();

          if($ext === 'jpg' || $ext === 'png'){

            $file = $user->name.'_'.date('YmdHis').rand(1,999).'.'.$ext;

            \Image::make($request->file("photo"))->save(public_path('img/profile/').$file);

            //$user->photo = public_path('img/user/').$user->photo;
            $user->photo = $file;
            $olduserPhoto = public_path('img/profile/').$currentphoto;
            if(file_exists($olduserPhoto)){
                @unlink($olduserPhoto);
            }

            }else {
                return['message'=>'Due to Security Issue we only accept Image Extention of jpg or png only'];
                exit();
            }
      }

       $user->save();

       return $user;

    }


    public function getArtistBySalonAndServiceId(Request $request) {

      $salon_id = $request->salon_id;

      $service_id = $request->service_id;

      $users = User::select('*')->where('salon_id', '=', $salon_id)->where('type', '=', 3)->where('services_id', '!=', NULL)->get();

      $artists = [];

      foreach($users as $user) {
        $data = explode(',', $user->services_id);
        for($i=0;$i<count($data);$i++) {
          if($data[$i] == $service_id) {
            $artists[] = $user;
          }
        }
      }

       return $artists;

    }

}
