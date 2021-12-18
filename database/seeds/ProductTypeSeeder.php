<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            'product_type' => 'NFC Card',
            'description' => 'NFC Card'
        ]);

        DB::table('product_types')->insert([
            'product_type' => 'NFC Tag',
            'description' => 'NFC Tag'
        ]);
    }
}
