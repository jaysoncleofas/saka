<?php

namespace Database\Seeders;

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
        $this->call([
            RoleSeeder::class,
            RoomSeeder::class,
            CottageSeeder::class,
            BreakfastSeeder::class,
            EntranceFeeSeeder::class,
            ResortSeeder::class
        ]);
        \App\Models\User::factory(3)->create();
        \App\Models\Guest::factory(3)->create();
    }
}
