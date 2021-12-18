<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

//Auth::routes();


Route::get('{path}', 'API\AuthController@user')->where( 'path' , '([A-z\d\-\/_.]+)?' );
