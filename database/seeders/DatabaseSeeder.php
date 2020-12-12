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
        ]);
        \App\Models\User::factory(11)->create();
        \App\Models\Client::factory(11)->create();
    }
}
