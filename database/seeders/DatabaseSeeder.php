<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{Client, Order};
use App\Models\{User};
use App\Models\{Product};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'Password1',
        ]);

        Client::factory(100)->create();

        Product::factory(20)->create();

        Order::factory(100)->create();


    }
}
