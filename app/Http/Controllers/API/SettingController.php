<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SettingLegal;
use App\SettingSocial;
use App\SettingBanner;
use App\SettingVideo;
use App\SettingFaq;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lang_code = Auth::user()->lang_code;
        $SettingLegal = SettingLegal::select('function as section',$lang_code.' as data')->where("deleted_at", "=", null)->get();
        $SettingSocial = SettingSocial::select('*')->where("deleted_at", "=", null)->get();
        $SettingBanner = SettingBanner::select('*')->where("is_active", "=", 1)->where("deleted_at", "=", null)->get();
        $countBanner = count($SettingBanner);
        for ($i = 0; $i <= $countBanner-1; $i++) {$SettingBanner[$i]->banner_img = url('/img/settings')."/".$SettingBanner[$i]->banner_img;}
        $SettingVideo = SettingVideo::select('*')->where("is_active", "=", 1)->where("deleted_at", "=", null)->get();
        $countVideo = count($SettingVideo);
        for ($i = 0; $i <= $countVideo-1; $i++) {$SettingVideo[$i]->video = url('/img/settings')."/".$SettingVideo[$i]->video;}
        
        return ["Legal"=>$SettingLegal, "Social"=>$SettingSocial,"Banners"=>$SettingBanner,"Videos"=>$SettingVideo];
    }

    public function singlesetting(Request $request)
    {
        $lang_code = Auth::user()->lang_code;
        $is_admin = Auth::user()->is_admin;

        $Legal = array( "Legal");        
        if(in_array($request->input('function'), $Legal)){
            if($request->input('section')){
                $SettingLegal = SettingLegal::select('function as section',$lang_code.' as data')->where("function", "=", $request->input('section'))->where("deleted_at", "=", null)->first();
                if(!isset($SettingLegal->data)){return ['message'=>"Data Doesn't Exist.."];}
            }else{
                $SettingLegal = SettingLegal::select('function as section',$lang_code.' as data')->where("deleted_at", "=", null)->get();
            }
            return $SettingLegal;
        }

        $Social = array( "Social");
        if(in_array($request->input('function'), $Social)){
            if($request->input('id')){
                $SettingSocial = SettingSocial::select('*')->where("id", "=", $request->input('id'))->where("deleted_at", "=", null)->first();
                if(!isset($SettingSocial->id)){return ['message'=>"Data Doesn't Exist.."];}
            }else{
                $SettingSocial = SettingSocial::select('*')->where("deleted_at", "=", null)->paginate(10);
            }
            
            return $SettingSocial;
        }

        $Banner = array( "Banner");
        if(in_array($request->input('function'), $Banner)){
            if($is_admin){
              if($request->input('id')){
                $SettingBanner = SettingBanner::select('*')->where("id", "=", $request->input('id'))->where("deleted_at", "=", null)->first();
                if(!isset($SettingBanner->id)){return ['message'=>"Data Doesn't Exist.."];}
                $SettingBanner->banner_img = url('/img/settings')."/".$SettingBanner->banner_img;
              }else{
                $SettingBanner = SettingBanner::select('*')->where("deleted_at", "=", null)->get();
                $countBanner = count($SettingBanner);
            for ($i = 0; $i <= $countBanner-1; $i++) {$SettingBanner[$i]->banner_img = url('/img/settings')."/".$SettingBanner[$i]->banner_img;}
              }
            }else{
              $SettingBanner = SettingBanner::select('*')->where("is_active", "=", 1)->where("deleted_at", "=", null)->get();
              $countBanner = count($SettingBanner);
            for ($i = 0; $i <= $countBanner-1; $i++) {$SettingBanner[$i]->banner_img = url('/img/settings')."/".$SettingBanner[$i]->banner_img;}
            }
            return $SettingBanner;
        }

        $Video = array( "Video");
        if(in_array($request->input('function'), $Video)){
            if($is_admin){
                if($request->input('id')){
                    $SettingVideo = SettingVideo::select('*')->where("id", "=", $request->input('id'))->where("deleted_at", "=", null)->first();
                    if(!isset($SettingVideo->id)){return ['message'=>"Data Doesn't Exist.."];}
                    $SettingVideo->video = url('/img/settings')."/".$SettingVideo->video;
                }else{
                    $SettingVideo = SettingVideo::select('*')->where("deleted_at", "=", null)->get();
                    $countVideo = count($SettingVideo);
            for ($i = 0; $i <= $countVideo-1; $i++) {$SettingVideo[$i]->video = url('/img/settings')."/".$SettingVideo[$i]->video;}
                }
            }else{
                $SettingVideo = SettingVideo::select('*')->where("is_active", "=", 1)->where("deleted_at", "=", null)->get();
                $countVideo = count($SettingVideo);
            for ($i = 0; $i <= $countVideo-1; $i++) {$SettingVideo[$i]->video = url('/img/settings')."/".$SettingVideo[$i]->video;}
            }
            
            return $SettingVideo;
        }

        $FAQ = array( "FAQ");
        if(in_array($request->input('function'), $FAQ)){
            if($request->input('question_no')){
                $SettingFaq = SettingFaq::select('*')->where("question_no", "=", $request->input('question_no'))->where("lang_code", "=", $lang_code)->where("deleted_at", "=", null)->first();
                if(!isset($SettingFaq->id)){return ["message" => "Data Doesn't Exist.."];}
            }else{
                $SettingFaq = SettingFaq::select('*')->where("lang_code", "=", $lang_code)->where("deleted_at", "=", null)->get();
            }

            return $SettingFaq;
        }
        
        return ["message" => "Please Pass calling function first"];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->is_admin){
            return['message'=>'Ohh Nigga This How You Gonna Hack Us..'];
        }

        $legal = array( "T&C", "CGV-CGU", "Legal-Notice");        
        if(in_array($request->input('function'), $legal)){
            $data = $request->validate([
                'function' => 'string|max:100',
                'data' => 'string|required|max:9000000',
            ]);

            $settinglegal = SettingLegal::select('id')->where('function','=',$request->input('function'))->first();
            $settinglegal = SettingLegal::find($settinglegal->id);
            $settinglegal->function = $request->input('function');
            if(Auth::user()->lang_code == 'EN'){$settinglegal->EN = $request->input('data');}
            if(Auth::user()->lang_code == 'FR'){$settinglegal->FR = $request->input('data');}
            if(Auth::user()->lang_code == 'SP'){$settinglegal->SP = $request->input('data');}
            $settinglegal->save(); 
            return  $settinglegal;         
        }

        $social = array( "Social");        
        if(in_array($request->input('function'), $social)){
            $data = $request->validate([
                'name' => 'required|string|max:100',
                'link' => 'required|string|max:1000'
            ]);

            $Settingsocial = SettingSocial::select('id')->where('name','=',$request->input('name'))->first();
            if(isset($Settingsocial->id)){
                $Settingsocial = SettingSocial::find($Settingsocial->id);
            }else{
                $Settingsocial = new SettingSocial;    
            }
            $Settingsocial->name = $request->input('name');
            $Settingsocial->link = $request->input('link');
            $Settingsocial->save();
            return  $Settingsocial; 
        }

        $banner = array( "Banner");        
        if(in_array($request->input('function'), $banner)){
            $data = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'is_active' => 'required|numeric|max:1'
            ]);
            if($request->input('edit_id')){
                $Settingbanner = SettingBanner::select('id','banner_img')->where('id','=',$request->input('edit_id'))->first();
                 if(isset($Settingbanner->id)){$exist = 1;}else{return['message'=>'Invalid Banner Id'];}
             }
            if(isset($exist)){
                $Settingbanner = SettingBanner::find($Settingbanner->id);
                $currentphoto = public_path('img/settings/').$Settingbanner->banner_img;
                if(file_exists($currentphoto)){ @unlink($currentphoto); }
            }else{
                $Settingbanner = new SettingBanner;    
            }
            $Settingbanner->banner_img = 'banner-image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('img/settings'), $Settingbanner->banner_img);
            $Settingbanner->is_active = $request->input('is_active');
            $Settingbanner->save();
            return  $Settingbanner; 
        }

        $video = array( "Video");        
        if(in_array($request->input('function'), $video)){
            $data = $request->validate([
                'video_name' => 'required|string|max:100',
                'video' =>'required|mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040',
                'is_active' => 'required|numeric|max:1'
            ]);
            if($request->input('edit_id')){
                $Settingvideo = SettingVideo::select('id','video')->where('id','=',$request->input('edit_id'))->first();
                if(isset($Settingvideo->id)){$exist = 1;}else{return['message'=>'Invalid Video Id'];}
            }
            if(isset($exist)){
                $Settingvideo = SettingVideo::find($Settingvideo->id);
                $currentvideo = public_path('img/settings/').$Settingvideo->video;
                if(file_exists($currentvideo)){ @unlink($currentvideo); }
            }else{
                $Settingvideo = new SettingVideo;    
            }
            $video=$data['video'];
            $Settingvideo->video = 'video'.time().'.'.$video->extension();
            $video->move(public_path('img/settings'), $Settingvideo->video);
            $Settingvideo->video_name = $request->input('video_name');
            $Settingvideo->is_active = $request->input('is_active');
            $Settingvideo->save();
            return  $Settingvideo; 
        }

        $faq = array( "FAQ");        
        if(in_array($request->input('function'), $faq)){
            $data = $request->validate([
                'question_no' => 'string|max:900000000',
                'question' => 'required|string|max:1000',
                'answer' => 'required|string|max:900000000'
            ]);
            if($request->input('question_no')){
                $Settingfaq = SettingFaq::select('id')->where('question_no','=',$request->input('question_no'))->where('lang_code','=',Auth::user()->lang_code)->first();
                if(isset($Settingfaq->id)){$exist = 1;}else{return['message'=>'Invalid Question Id'];}           
            }else{
                $question_no = time();
                $languages = array('EN','FR','SP');
                foreach($languages as $language){
                    $Settingfaq = new SettingFaq;
                    $Settingfaq->question_no = $question_no;
                    $Settingfaq->question = $request->input('question');
                    $Settingfaq->answer = $request->input('answer');
                    $Settingfaq->lang_code = $language;
                    $Settingfaq->save();
                }
            }
            if(isset($exist)){
                $Settingfaq = SettingFaq::find($Settingfaq->id);
                $Settingfaq->question_no = $request->input('question_no');
                $Settingfaq->question = $request->input('question');
                $Settingfaq->answer = $request->input('answer');
                $Settingfaq->save();
            }
            return  $Settingfaq; 
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }

    public function deletesetting(Request $request)
    {
        $lang_code = Auth::user()->lang_code;
        $is_admin = Auth::user()->is_admin;
        if(!$is_admin){
            return['message'=>'Ohh Nigga This How You Gonna Hack Us..'];
        }

        $Banner = array( "Banner");        
        if(in_array($request->input('function'), $Banner) && $request->input('id')){
              $SettingBanner = SettingBanner::find($request->input('id'));
            if(isset($SettingBanner->id)){
                $SettingBanner->deleted_at = date('Y-m-d H:i:s');
                $SettingBanner->save();
                return ['message'=> "Banner Deleted Sucessfull"];
            }else{
                return ['message'=> "Data Doesn't Exist"];
            }
        }

        $Video = array( "Video");        
        if(in_array($request->input('function'), $Video) && $request->input('id')){
              $SettingVideo = SettingVideo::find($request->input('id'));
            if(isset($SettingVideo->id)){
                $SettingVideo->deleted_at = date('Y-m-d H:i:s');
                $SettingVideo->save();
                return ['message'=> "Video Deleted Sucessfull"];
            }else{
                return ['message'=> "Data Doesn't Exist"];
            }
        }

        $Social = array( "Social");        
        if(in_array($request->input('function'), $Social) && $request->input('id')){
              $SettingSocial = SettingSocial::find($request->input('id'));
            if(isset($SettingSocial->id)){
                $SettingSocial->deleted_at = date('Y-m-d H:i:s');
                $SettingSocial->save();
                return ['message'=> "Social Deleted Sucessfull"];
            }else{
                return ['message'=> "Data Doesn't Exist"];
            }
        }

        $FAQ = array( "FAQ");        
        if(in_array($request->input('function'), $FAQ) && $request->input('question_no')){

            $SettingFaq2 = SettingFaq::select('id')->where('question_no','=',$request->input('question_no'))->get();
            if(!isset($SettingFaq2[0]->id)){
                    return ['message'=> "Data Doesn't Exist Delete Unsucessfull"];
                }
            $countFaq = count($SettingFaq2);
            for ($i = 0; $i <= $countFaq-1; $i++) {
                $SettingFaq = SettingFaq::find($SettingFaq2[$i]->id);
                if(isset($SettingFaq->id)){
                    $SettingFaq->deleted_at = date('Y-m-d H:i:s');
                    $SettingFaq->save();
                    $message = "FAQ Deleted Sucessfully";
                }else{
                    $message = "Data Doesn't Exist";
                }
            }
            return ['message'=> $message];
        }
        return ['message'=> "Data Doesn't Exist"];
    }
}
