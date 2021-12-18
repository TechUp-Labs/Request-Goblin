<?php

use Illuminate\Database\Seeder;

class SettingSocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_socials')->insert([
            'name' => 'Facebook',
            'link' => 'https://facebook.com/skyple'
        ]);

        DB::table('setting_socials')->insert([
            'name' => 'Instagram',
            'link' => 'https://instagram.com/skyple'
        ]);

        DB::table('setting_socials')->insert([
            'name' => 'LinkedIn',
            'link' => 'https://www.linkedin.com/in/skyple-402290215/'
        ]);

        DB::table('setting_socials')->insert([
            'name' => 'Email',
            'link' => 'mailto:munshiji.zakir@gmail.com'
        ]);
    }
}
