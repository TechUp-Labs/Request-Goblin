<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('login','API\AuthController@user')->name('loginroute');
Route::post('login','API\AuthController@login');
Route::post('register','API\AuthController@register');
Route::post('new_register','API\AuthController@register');
Route::post('forget','API\AuthController@forget');
Route::post('reset','API\AuthController@reset');
Route::get('getproductopenlist','API\ProductController@getproductopenlist');
Route::get('openproduct/{id}','API\ProductController@openproduct');
Route::post('openenquiry','API\EnquiryController@openenquiry');


Route::middleware('auth:sanctum')->group(function (){
    
Route::get('generate_goblin/{nos}','API\GoblinController@generate_goblin');
Route::apiResources(['employee' => 'API\EmployeeController']);


Route::post('registeradmin','API\AuthController@register');
Route::get('login','API\AuthController@user');
Route::get('dashboard','API\AuthController@dashboard');
Route::get('getuserdetail/{id}','API\AuthController@getuserdetail');
Route::get('listadmin','API\AuthController@index');
Route::get('userlist','API\AuthController@userlist');
Route::post('user/{id}','API\AuthController@update');
Route::post('logout','API\AuthController@logout');
Route::delete('deleteuser/{id}','API\AuthController@delete');

Route::apiResources(['products' => 'API\ProductController']);
Route::apiResources(['userAddress' => 'API\UserAddressController']);
Route::apiResources(['enquiry' => 'API\EnquiryController']);


Route::get('producttypes','API\ProductController@type');

Route::post('generatecards','API\ProductController@generatecards');
Route::post('validatecards','API\ProductController@validatecards');
Route::post('assigncards','API\ProductController@assigncards');
Route::post('updatepassword','API\AuthController@updatepassword');


Route::get('statistic/{id}','API\StatisticController@show');
Route::get('portalstatistic/{id}','API\StatisticController@showportal');

Route::apiResources(['settings' => 'API\SettingController']);
Route::post('singlesetting','API\SettingController@singlesetting');
Route::post('deletesetting','API\SettingController@deletesetting');

});
















