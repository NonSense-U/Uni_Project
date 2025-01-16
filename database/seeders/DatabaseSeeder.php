<?php

namespace Database\Seeders;

use App\Models\StoreInventory;
use App\Models\StoreOwner;
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
        $user = User::factory()->create([
            'username' => 'Malek',
            'email' => 'akikon@gmail.com',
            'phoneNumber' => '0992522375',
            'password' => 'BeAwesome'
        ]);
        $user->assignRole('store_owner');
        $user->Save();
        StoreOwner::create([
            'user_id' => $user->id,
        ]);

        $this->call(CustomerSeeder::class);
        $this->call(StoreOwnerSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
