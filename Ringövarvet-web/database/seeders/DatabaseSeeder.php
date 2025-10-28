<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Unit;
use App\Models\Property;
use App\Models\SubcategoryProperty;
use App\Models\Product;
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

        Category::factory(4)->create();

        Subcategory::factory(15)->create();

        Unit::factory(15)->create();

        Property::factory(30)->create();

        SubcategoryProperty::factory(100)->create();

        Product::factory(100)->create();
    }
}
