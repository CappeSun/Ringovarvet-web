<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Property;
use App\Models\Subcategory;
use App\Models\SubcategoryProperty;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductProperty>
 */
class ProductPropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first();
        $subcategory = Subcategory::where('id', $product->subcategoryId)->first();
        $subcategoryProperty = SubcategoryProperty::where('subcategoryId', $subcategory->id)->inRandomOrder()->first();

        return [
            'productId' => $product->id,
            'propertyId' => Property::where('id', $subcategoryProperty->id)->inRandomOrder()->first()->id
        ];
    }
}
