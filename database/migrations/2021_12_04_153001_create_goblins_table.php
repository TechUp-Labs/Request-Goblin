<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoblinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goblins', function (Blueprint $table) {
            $table->id();
            $table->string('hash_code', 100)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->string('status', 100)->nullable();
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goblins');
    }
}
