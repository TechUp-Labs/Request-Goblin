<?php

use Illuminate\Database\Seeder;

class SettingLegalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_legals')->insert([
            'function' => 'CGV-CGU',
            'EN' => 'EN CGV-CGU Start Here',
            'SP' => 'SP CGV-CGU Start Here',
            'FR' => 'FR CGV-CGU Start Here',

        ]);

        
        DB::table('setting_legals')->insert([
            'function' => 'Legal-Notice',
            'EN' => 'EN Legal Notice Start Here',
            'SP' => 'SP Legal Notice Start Here',
            'FR' => 'FR Legal Notice Start Here',

        ]);

        DB::table('setting_legals')->insert([
            'function' => 'T&C',
            'EN' => 'EN Terms & Condition Start Here',
            'SP' => 'SP Terms & Condition Start Here',
            'FR' => 'FR Terms & Condition Start Here',

        ]);
    }
}
