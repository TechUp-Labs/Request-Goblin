<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UserSeeder::class, ProductTypeSeeder::class, SettingLegalSeeder::class, SettingSocialSeeder::class, SettingBannerSeeder::class,SettingVideoSeeder::class, SettingFAQSeeder::class]);
         
         //$this->call([ModuleSeeder::class,  ModuleLangSeeder::class]);
    }
}
