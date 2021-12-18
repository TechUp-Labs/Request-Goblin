<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'fullname' => 'Super Admin',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'profile_img' => 'Dp.jpg',
            'email' => 'a'.'@example.com',
            'mobile' => '0123456789',
            'password' => Hash::make('12345678'),
            'dob' => '1999-06-05',
            'gender' => 'Male',
            'role' => 'Super Admin',
            'is_admin' => '1',
            'lang_code' => 'EN',
        ]);
    }
}
