<?php

use Illuminate\Database\Seeder;

class SettingVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_videos')->insert([
            'video_name' => 'Shop Website',
            'video' => 'video.mp4',
            'is_active' => 1
        ]);

        DB::table('setting_videos')->insert([
            'video_name' => 'NFC Website',
            'video' => 'video.mp4',
            'is_active' => 1
        ]);
    }
}
