<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => 'Red', 'description' => 'red color', 'hex_code' => '#FF0000'],
            ['name' => 'Green', 'description' => 'green color', 'hex_code' => '#00FF00'],
            ['name' => 'Blue', 'description' => 'blue color', 'hex_code' => '#0000FF'],
            ['name' => 'Black', 'description' => 'black color', 'hex_code' => '#000000'],
            ['name' => 'White', 'description' => 'white color', 'hex_code' => '#FFFFFF'],
        ];

        DB::table('product_colors')->insert($colors);
    }
}
