<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Property;
use App\Models\SubcategoryProperty;
use App\Models\ProductProperty;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'subcategoryId' => Subcategory::inRandomOrder()->first()->id
        ];
    }

    public function configure()
    {
        // Create properties

        return $this->afterCreating(function (Product $product) {
            $subcategoryProperties = SubcategoryProperty::where('subcategoryId', $product->subcategoryId)->get();

            $subcategoryProperties->each(function ($entry) use (&$product) {
                new ProductProperty([
                    'productId' => $product->id,
                    'propertyId' => $entry->propertyId,
                    'value' => fake()->name()
                ])->save();
            });
        });
    }
}
