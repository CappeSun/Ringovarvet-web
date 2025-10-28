<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subcategory;
use App\Models\Property;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubcategoryProperty>
 */
class SubcategoryPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subcategoryId' => Subcategory::inRandomOrder()->first()->id,
            'propertyId' => Property::inRandomOrder()->first()->id
        ];
    }
}
