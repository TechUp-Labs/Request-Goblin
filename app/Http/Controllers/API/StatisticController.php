<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Portal;
use App\Statistic;
use App\StatisticPortal;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
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
    public function store($id)
    {
        if(!Auth::user()->is_admin){
            $statistic = new Statistic;
            $statistic->product_id = $id;
            $statistic->user_id = Auth::user()->id;
            $statistic->save();
        }
    }

    public function storeportal($id)
    {
       /* if(!Auth::user()->is_admin){
            $statisticportal = StatisticPortal::where("portal_id","=",$id)->first();
            if(!isset($statisticportal->id)){
                $statisticportal = new StatisticPortal;
                $statisticportal->portal_id = $id;
                $statisticportal->count = 1;
            }else{
                $statisticportal = StatisticPortal::find($statisticportal->id);
                $statisticportal->count = $statisticportal->count + 1;
            } 
            $statisticportal->save();
        } */
        
        if(!Auth::user()->is_admin){
            $statisticportal = new StatisticPortal;
            $statisticportal->portal_id = $id;
            $statisticportal->user_id = Auth::user()->id;
            $statisticportal->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->is_admin){
            $isnull = Statistic::where("product_id","=",$id)->first();
            if(!isset($isnull->id)){
                return["message"=>"Statistic Doesn't Exist"];
            }
            $days = array(0,1,2,3,4,5,6);
            $i = 0;
            foreach($days as $day){
                $day2 = $day+1;
                $i++;
                $startdate = date('Y-m-d', strtotime(now(). ' - '.$day2.' days'));            
                $enddate = date('Y-m-d', strtotime(now(). ' - '.$day.' days'));
                $statisticproduct["date_$i"] = $startdate;
                $statisticproduct["count_$i"] = Statistic::select("*")
                               ->where("product_id","=",$id)
                               ->whereBetween('created_at',[$startdate, $enddate])
                               ->count();
            }
            return $statisticproduct;
        }else{
            return["message"=>"Ohh Nigga This How You Gonna Hack Us ?"];
        }
    }

    public function showportal($id)
    {
        if(!Auth::user()->is_admin){
            $portal = Portal::select("*")
                      ->where('portals.id', $id)
                      ->where("portals.deleted_at", "=", null)
                      ->Where(function($query) {
                          $query->orwhere('portals.owner_id', Auth::user()->id)
                                ->orwhere('portals.user_id', Auth::user()->id)
                                ->orwhere('portals.manager_id', Auth::user()->id);
                      })
                      ->first();
            if(!isset($portal->id)){
                return["message" => "Portals Doesn't Belongs To You"];
            }
        }
        
        $days = array(0,1,2,3,4,5,6);
        $i = 0;
        foreach($days as $day){
            $i++;
            $day2 = $day+1;
            $startdate = date('Y-m-d', strtotime(now(). ' - '.$day2.' days'));            
            $enddate = date('Y-m-d', strtotime(now(). ' - '.$day.' days'));
            $statisticportal["date_$i"] = $startdate;
            $statisticportal["count_$i"] = StatisticPortal::select("*")->where("portal_id","=",$id)->whereBetween('created_at',[$startdate, $enddate])->count();
        }
        
        return $statisticportal;
        
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
