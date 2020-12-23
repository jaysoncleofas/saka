<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resorts')->insert([
            ['name' => 'Saka Resort', 'phone' => '0906 503 9647', 'email' => 'sakaresort@yahoo.com', 'address' => 'Purok 2, San Juan De Valdez San Jose Tarlac', 'day' => '9am - 5pm', 'night' => '5pm - 9pm', 'overnight' => '2pm - 11am', 'breakfastPrice' => 300, 'facebook' => 'https://www.facebook.com/SAKARESORT/', 'instagram' => 'https://www.instagram.com/explore/locations/1013334217/saka-resort-tarlac/', 'twitter' => 'https://twitter.com/'],
        ]);
    }
}
