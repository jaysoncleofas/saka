<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CottageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cottages')->insert([
            [
                'name' => 'Toraja',
                'price' => '800',
                'nightPrice' => '900',
                'descriptions' => 'Good for up to 12pax, 2-storey cottage',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Open Kubo',
                'price' => '700',
                'nightPrice' => '800',
                'descriptions' => 'Good for up to 12pax, Bungalow type',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Toraja (Big)',
                'price' => '1500',
                'nightPrice' => '1600',
                'descriptions' => 'Good for up to 20pax, Pool side, with own entrance, 2-storey cottage',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Mini Pavilion',
                'price' => '1000',
                'nightPrice' => '1100',
                'descriptions' => 'Good for up to 15pax',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Small Pavilion',
                'price' => '1500',
                'nightPrice' => '1600',
                'descriptions' => 'Good for up to 25pax',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Grand Pavilion',
                'price' => '3000',
                'nightPrice' => '3100',
                'descriptions' => 'Good for up to 40pax, Pool side, with own entrance',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
