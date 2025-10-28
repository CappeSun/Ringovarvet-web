<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Property;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Unit::factory(15)->create();

        Category::factory(5)->create();

        Subcategory::factory(20)->create();

        Property::factory(30)->create();
    }
}
