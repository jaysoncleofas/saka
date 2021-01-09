<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        // \App\Models\User::factory(3)->create();
        // \App\Models\Guest::factory(3)->create();
        DB::table('users')->insert([
            [
                'firstName' => 'System',
                'lastName' => 'Administrator',
                'role_id' => 1,
                'email' => 'system.admin@saka.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
            ]
        ]);
    }
}
