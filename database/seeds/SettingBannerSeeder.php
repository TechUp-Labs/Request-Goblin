<?php

use Illuminate\Database\Seeder;

class SettingBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_banners')->insert([
            'banner_img' => 'banner-image.jpg',
            'is_active' => 1
        ]);
    }
}
