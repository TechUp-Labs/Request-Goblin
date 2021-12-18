<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('fullname', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->bigInteger('mobile')->unique()->nullable();
            $table->string('password', 100);
            $table->integer('is_admin')->length(1)->nullable();
            $table->string('role', 100)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('designation', 100)->nullable();
            $table->date('dob')->nullable();
            $table->string('company_logo', 100)->nullable();
            $table->string('profile_img', 100)->nullable();
            $table->string('gender')->length(100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('lang_code', 15)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
