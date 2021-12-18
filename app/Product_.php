<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name', 'product_img', 'product_code', 'product_price','product_cost','product_img','category_id','is_featured', 'brand_id', 'product_detail', 'reference_code', 'variant_array', 'is_varified', 'is_cod'
    ];
}
