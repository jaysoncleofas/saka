<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreakfastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('breakfasts')->insert([
            ['title' => 'Ham', 'price' => 100],
            ['title' => 'Hotdogs', 'price' => 100],
            ['title' => 'Skinless longganisa', 'price' => 100],
            ['title' => 'Tocino', 'price' => 100],
        ]);
    }
}
