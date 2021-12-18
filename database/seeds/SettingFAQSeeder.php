<?php

use Illuminate\Database\Seeder;

class SettingFAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = array('EN','FR','SP');
        foreach($languages as $language){
            DB::table('setting_faqs')->insert([
                'question' => 'Question One in '.$language.' ?',
                'answer' => 'Answer in '.$language.'.',
                'question_no' => 1636917982,
                'lang_code' => $language,
            ]);
        }
    }
}
