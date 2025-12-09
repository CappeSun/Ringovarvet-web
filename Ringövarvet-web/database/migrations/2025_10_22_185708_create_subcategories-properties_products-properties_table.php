<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('subcategories-properties', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('subcategoryId');
			$table->foreign('subcategoryId')->references('id')->on('subcategories');
			$table->unsignedBigInteger('propertyId');
			$table->foreign('propertyId')->references('id')->on('properties');
			$table->timestamps();
		});

		Schema::create('products-properties', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('productId');
			$table->foreign('productId')->references('id')->on('products');
			$table->unsignedBigInteger('propertyId');
			$table->foreign('propertyId')->references('id')->on('properties');
			$table->string('value')->default('Unset value');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('subcategories-properties');
		Schema::dropIfExists('products-properties');
	}
};
