<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id');
            $table->float('price', 8, 2);
            $table->string('product_imgs', 1000)->nullable();
            $table->string('product_imgs1', 1000)->nullable();
            $table->string('product_imgs2', 1000)->nullable();
            $table->string('product_imgs3', 1000)->nullable();
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
