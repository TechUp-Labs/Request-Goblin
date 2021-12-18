<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name', 'email', 'password','type','bio','photo','otp','mobile'
        'name', 'password', 'fullname', 'mobile', 'mobile_verified', 'type', 'bio', 'address', 'city', 'state_code', 'country_code', 'pin', 'account_no', 'ifsc_code', 'photo', 'email', 'email_verified_at', 'otp', 'salon_id', 'services_id', 'is_active', 'lat', 'lng', 'areainkm', 'is_admin', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'otp'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


}
