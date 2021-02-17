<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntranceFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entrancefees')->insert([
            ['title' => 'Adults', 'price' => 100, 'nightPrice' => 150, 'overnightPrice' => 180],
            ['title' => 'Kids', 'price' => 100, 'nightPrice' => 150, 'overnightPrice' => 180],
            ['title' => 'Senior Citizen', 'price' => 80, 'nightPrice' => 130, 'overnightPrice' => 150],
        ]);
    }
}