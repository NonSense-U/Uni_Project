<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(CustomerSeeder::class);
        User::factory()->create([
            'username' => 'Malek',
            'email' => 'akikon@gmail.com',
            'phoneNumber' => '0992522375',
            'password' => 'BeAwesome'
        ]);
    }
}
