<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\StatisticController;
use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\ProductLang;
use App\ProductType;
use App\GenerateProduct;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        
        if(isset($_GET['query'])){
            $searchquery = $_GET['query'];
            $product_details = Product::select("*")
            ->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where("products.deleted_at", "=", null)->where("product_langs.lang_code", "=", Auth::user()->lang_code)
            ->where(function($query) use ($searchquery){
                                    $query->where('product_name','LIKE',"%$searchquery%")
                                    ->orWhere('price','LIKE',"%$searchquery%")
                                    ->orWhere('status','LIKE',"%$searchquery%")
                                    ->orWhere('product_type','LIKE',"%$searchquery%");
                                })
            ->paginate(5);
        }else{
            $product_details = Product::select("*")
            ->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where("products.deleted_at", "=", null)->where("product_langs.lang_code", "=", Auth::user()->lang_code)
            ->paginate(5);
        }
        

       $countproduct = count($product_details);
       for ($i = 0; $i <= $countproduct-1; $i++) {
         $product_details[$i]->product_imgs = url('/img/product')."/".$product_details[$i]->product_imgs;
         $product_details[$i]->product_imgs1 = url('/img/product')."/".$product_details[$i]->product_imgs1;
         $product_details[$i]->product_imgs2 = url('/img/product')."/".$product_details[$i]->product_imgs2;
         $product_details[$i]->product_imgs3 = url('/img/product')."/".$product_details[$i]->product_imgs3;
         $stock_count = GenerateProduct::select("product_id")->where("product_id","=",$product_details[$i]->product_id)->where("user_id","=",null)->count();
         $product_details[$i]->stock_count = $stock_count;
        }      

       return $product_details;
    }

    public function getproductopenlist()
    {
        if(isset($_GET['lang_code'])){
                $lang_code = $_GET['lang_code'];
        }else{
                $lang_code = "FR";
        }
        if(isset($_GET['query'])){
            $searchquery = $_GET['query'];
            $product_details = Product::select("*")
            ->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where("products.deleted_at", "=", null)->where("product_langs.lang_code", "=", $lang_code)
            ->where(function($query) use ($searchquery){
                                    $query->where('product_name','LIKE',"%$searchquery%")
                                    ->orWhere('price','LIKE',"%$searchquery%")
                                    ->orWhere('status','LIKE',"%$searchquery%")
                                    ->orWhere('product_type','LIKE',"%$searchquery%");
                                })
            ->paginate(5);
        }else{
            $product_details = Product::select("*")
            ->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')
            ->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where("products.deleted_at", "=", null)->where("product_langs.lang_code", "=", $lang_code)
            ->paginate(5);
        }
        

        $countproduct = count($product_details);
       for ($i = 0; $i <= $countproduct-1; $i++) {
         $product_details[$i]->product_imgs = url('/img/product')."/".$product_details[$i]->product_imgs;
         $product_details[$i]->product_imgs1 = url('/img/product')."/".$product_details[$i]->product_imgs1;
         $product_details[$i]->product_imgs2 = url('/img/product')."/".$product_details[$i]->product_imgs2;
         $product_details[$i]->product_imgs3 = url('/img/product')."/".$product_details[$i]->product_imgs3;
         $stock_count = GenerateProduct::select("product_id")->where("product_id","=",$product_details[$i]->product_id)->where("user_id","=",null)->count();
         $product_details[$i]->stock_count = $stock_count;
        }      

       return $product_details;
    }

    public function type()
    {
        $product_type = ProductType::select("*")->where("deleted_at", "=", null)
        ->get();

        return $product_type;
    }

    public function generatecards(Request $request, Product $product)
    {
        $data = $request->validate([
                'total'=> 'numeric|required|between:1,50',
                'product_id'=> 'required|exists:products,id',
        ]);

        $product_id = $request->input('product_id');
        $total = $request->input('total');

        if(!Auth::user()->is_admin){
           return['message'=> "Ohh Nigga This How You Gonna Hack Us ?"];           
        } 

        for ($i = 1; $i <= $total; $i++) {
            $generateproduct = new GenerateProduct;
            $generateproduct->product_id = $product_id;
            $generateproduct->user_id = null;
            $generateproduct->hash = "P".$product_id."I".$total."Z".(rand(100000,999999));
            if($i < 10){
                $zero = 0;
            }else{
                $zero = null;
            }
            $generateproduct->save();
            $url["URL_$zero$i"] = "https://jeansatlas.winayak.com/#/cards/".$generateproduct->hash;
        }

        return $url;
        //return['message'=>'Unique Link Generated',$url];
    }

    public function validatecards(Request $request, GenerateProduct $generateproduct)
    {

        if(!isset(Auth::user()->is_admin)){
            return["message"=>"You are not Admin.."];
        }

        $data = $request->validate([
            'card_hash'=> 'string|max:10000|required|exists:generate_products,hash',
        ]);

        $product_hash = $request->input('card_hash');

        $lang = Auth::user()->lang_code;

        $product_details = GenerateProduct::select('generate_products.id as id','generate_products.product_id as product_id','hash','generate_products.user_id','product_type','product_name','product_langs.description','price','status','lang_code','product_imgs','product_imgs1','product_imgs2','product_imgs3','generate_products.created_at')->leftjoin('products', 'products.id', '=', 'generate_products.product_id')->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where('generate_products.hash', $product_hash)->where("product_langs.lang_code", "=", $lang)->where("generate_products.deleted_at", "=", null)->get();

       $countproduct = count($product_details);
       for ($i = 0; $i <= $countproduct-1; $i++) {
         $product_details[$i]->product_imgs = url('/img/product')."/".$product_details[$i]->product_imgs;
         $product_details[$i]->product_imgs1 = url('/img/product')."/".$product_details[$i]->product_imgs1;
         $product_details[$i]->product_imgs2 = url('/img/product')."/".$product_details[$i]->product_imgs2;
         $product_details[$i]->product_imgs3 = url('/img/product')."/".$product_details[$i]->product_imgs3;
         $product_details[$i]->created_at = date($product_details[$i]->created_at);
        }

       return $product_details;        
    }

    public function assigncards(Request $request)
    {
        $data = $request->validate([
            'card_hash'=> 'string|max:10000|required|exists:generate_products,hash',
        ]);

        $product_hash = $request->input('card_hash');

        if(!isset(Auth::user()->is_admin)){

            $order = Order::select("*")
                      ->where('orders.user_id', Auth::user()->id)
                      ->where('orders.status', 'Completed')
                      ->where("orders.deleted_at", "=", null)
                      ->first();
            if(isset($order->id)){
                    $generateproduct = GenerateProduct::select('*')->where("hash","=",$product_hash)->first();
                    if ($generateproduct->user_id == Auth::user()->id) {
                           return["message"=>"This Card is already assignd to you.."];
                        }
                    if ($generateproduct->user_id != null && $generateproduct->user_id != Auth::user()->id) {
                             return["message"=>"This card is already Occupied, Please Contact our Support Team.."];
                        }
                    if ($generateproduct->user_id == null) {
                            $generateproduct = GenerateProduct::find($generateproduct->id);
                            $generateproduct->user_id = Auth::user()->id;
                            $generateproduct->save();
                            return ["message" => "Card Successfully Assigned to ".Auth::user()->first_name];
                        }
            }else{
                  return["message"=>"Please Purchase Card First, Or Your Purchase Is Incomplete or Processing, Please Contact Our Support Team"];
            }  

            
        }else{
            return ["message" => "You are Admin, Please Make A Normal User Account if you own this card"];
        }

               
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, ProductLang $productlang)
    {
        if(!Auth::user()->is_admin){
           return['message'=> "Ohh Nigga This How You Gonna Hack Us ?"];           
        } 
        $data = $request->validate([
                'price'=> 'numeric|required|between:0,99999.99',
                'type_id'=> 'integer|required|max:11',
                'status' => 'required|integer|max:1000',
                'product_name' => 'string|required|max:100',
                'description' => 'string|required|max:10000',
                'lang_code' => 'string|max:15',
                'product_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3048',
                'product_img1' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3048',
                'product_img2' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3048',
                'product_img3' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3048'
        ]);

        $product->price = $request->input('price');
        $product->type_id = $request->input('type_id');
        $product->status = $request->input('status');
        
        

        if ($request->file("product_img")) {

                $ext = $request->file("product_img")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p101".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img"))->save(public_path('img/product/').$file);

                $product->product_imgs = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

        if ($request->file("product_img1")) {

                $ext = $request->file("product_img1")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p201".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img1"))->save(public_path('img/product/').$file);

                $product->product_imgs1 = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

        if ($request->file("product_img2")) {

                $ext = $request->file("product_img2")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p301".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img2"))->save(public_path('img/product/').$file);

                $product->product_imgs2 = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

        if ($request->file("product_img3")) {

                $ext = $request->file("product_img3")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p401".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img3"))->save(public_path('img/product/').$file);

                $product->product_imgs3 = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

            $product->save();
            
            $languages = array( "FR", "EN", "SP");
            foreach ($languages as $language) {
                $productlang = new ProductLang;
                $productlang->product_id = $product->id;
                $productlang->product_name = $request->input('product_name');
                $productlang->description = $request->input('description');
                $productlang->lang_code = $language;
                $productlang->save();
            }
           
        return ["Message" => "Success", $product, $productlang];



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product_details = Product::select("*")->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where('products.id', $id)->where("product_langs.lang_code", "=", Auth::user()->lang_code)->where("products.deleted_at", "=", null)->first();
        if(isset($product_details->id)){
         $product_details->product_imgs = url('/img/product')."/".$product_details->product_imgs;
         $product_details->product_imgs1 = url('/img/product')."/".$product_details->product_imgs1;
         $product_details->product_imgs2 = url('/img/product')."/".$product_details->product_imgs2;
         $product_details->product_imgs3 = url('/img/product')."/".$product_details->product_imgs3;
        

        $stock_count = GenerateProduct::select("product_id")->where("product_id","=",$id)->where("user_id","=",null)->count();
        $product_details->stock_count = $stock_count;

        }

        if(!isset($product_details->id)){
            return ['message'=> "Product Doesn't Exist"];
        }
       
       $Statistic = (new StatisticController)->store($id);
       return $product_details;
    }

    public function openproduct($id)
    {
      if(isset($_GET['lang_code'])){
            $lang_code = $_GET['lang_code'];
      }else{
            $lang_code = "FR";
      }
      //p101_20211008050502866.png

      $product_details = Product::select("*")->leftjoin('product_types', 'product_types.id', '=', 'products.type_id')->leftjoin('product_langs', 'product_langs.product_id', '=', 'products.id')->where('products.id', $id)->where("product_langs.lang_code", "=", $lang_code)->where("products.deleted_at", "=", null)->first();

      if(isset($product_details->id)){
        $product_details->product_imgs = url('/img/product')."/".$product_details->product_imgs;
         $product_details->product_imgs1 = url('/img/product')."/".$product_details->product_imgs1;
         $product_details->product_imgs2 = url('/img/product')."/".$product_details->product_imgs2;
         $product_details->product_imgs3 = url('/img/product')."/".$product_details->product_imgs3;
         $stock_count = GenerateProduct::select("product_id")->where("product_id","=",$id)->where("user_id","=",null)->count();
         $product_details->stock_count = $stock_count;
       }



       if(!isset($product_details->id)){
            return ['message'=> "Product Doesn't Exist"];
        }       

       return $product_details;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id, Productlang $productlang)
    {
        if(!Auth::user()->is_admin){
           return['message'=> "Ohh Nigga This How You Gonna Hack Us ?"];           
        } 
        $product = Product::find($id);
        $productl = Productlang::select("*")->where('product_id', $id)->where("product_langs.lang_code", "=", Auth::user()->lang_code)->first();

        

        if($productl->lang_code != Auth::user()->lang_code){

            $productlang->product_id = $id;
            $productlang->lang_code = Auth::user()->lang_code;

        }else{
            $productlang = Productlang::find($productl->id);
        }

        if($request->input('price')){ $product->price = $request->input('price'); }
        if($request->input('type_id')){ $product->type_id = $request->input('type_id'); }
        if($request->input('status')){ $product->status = $request->input('status'); }

        if($request->input('product_name')){$productlang->product_name = $request->input('product_name');}
        if($request->input('description')){$productlang->description = $request->input('description');}

        if ($request->file("product_img")) {

                $ext = $request->file("product_img")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p101".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img"))->save(public_path('img/product/').$file);

                $product->product_imgs = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

        if ($request->file("product_img1")) {

                $ext = $request->file("product_img1")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p201".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img1"))->save(public_path('img/product/').$file);

                $product->product_imgs1 = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

        if ($request->file("product_img2")) {

                $ext = $request->file("product_img2")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p301".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img2"))->save(public_path('img/product/').$file);

                $product->product_imgs2 = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }

        if ($request->file("product_img3")) {

                $ext = $request->file("product_img3")->extension();

                if($ext === 'jpg' || $ext === 'png'){

                $file = "p401".'_'.date('YmdHis').rand(1,999).'.'.$ext;

                \Image::make($request->file("product_img3"))->save(public_path('img/product/').$file);

                $product->product_imgs3 = $file;

                }else {
                    return['message'=>'Please change Image Extention to jpg or png'];
                    exit();
                }
        }
        
        $product->save();
        $productlang->save();       

        return [ $productlang, $product ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::user()->is_admin){
           return['message'=> "Ohh Nigga This How You Gonna Hack Us ?"];           
        } 
        if(Auth::user()->is_admin){
            $product = Product::find($id);
            if(isset($product->id)){
                $product->deleted_at = date('Y-m-d H:i:s');
                $product->save();
            }else{
                return ['message'=> "Product Doesn't Exist"];
            }
            return ['message'=> "Product Deleted Sucessfull"];
       }else{
            return ['message'=> "You are not authorized to delete Products"];
       }
    }
}
