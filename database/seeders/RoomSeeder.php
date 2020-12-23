<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert([
            [
                'name' => 'A House',
                'price' => '5000',
                'extraPerson' => '0',
                'extraPersonAvailable' => null,
                'descriptions' => 'Semi air-con room, private toilet & bath, private kitchen, Good for 5pax, Inclusive of entrance fee',
                'entrancefee' => 'Inclusive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Casita',
                'price' => '3500',
                'extraPerson' => '250',
                'extraPersonAvailable' => 2,
                'descriptions' => 'Air-con room, Dorm set up, Good for 6pax(max of 8), 250php for extra person, Private toilet & bath, Inclusive of entrance fee',
                'entrancefee' => 'Inclusive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Cuarto',
                'price' => '1500',
                'extraPerson' => '0',
                'extraPersonAvailable' => null,
                'descriptions' => 'Air-con room, Shared toilet & bath, Good for 2pax, Inclusive of entrance fee',
                'entrancefee' => 'Inclusive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Toraja B',
                'price' => '2000',
                'extraPerson' => '250',
                'extraPersonAvailable' => 2,
                'descriptions' => 'Air-con room, Good for 3pax, Shared toilet and bath, Maximum Capacity of 5pax, 250php for extra person, Inclusive of entrance fee',
                'entrancefee' => 'Inclusive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Toraja A',
                'price' => '2000',
                'extraPerson' => '0',
                'extraPersonAvailable' => null,
                'descriptions' => 'Non-aircon room, Good for 10pax, Shared toilet & bath, Exclusive of entrance fee, (Pool view)',
                'entrancefee' => 'Exclusive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Toraja',
                'price' => '1000',
                'extraPerson' => '0',
                'extraPersonAvailable' => null,
                'descriptions' => 'Non-aircon room, Good for 5pax, Shared toilet & bath, Exclusive of entrance fee',
                'entrancefee' => 'Exclusive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
