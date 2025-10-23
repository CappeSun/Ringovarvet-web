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
		Schema::create('categories', function (Blueprint $table) {
			$table->id();
			$table->string('name')->default('Nameless category');
			$table->timestamps();
		});

		Schema::create('subcategories', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('categoryId');
			$table->foreign('categoryId')->references('id')->on('categories');
			$table->string('name')->default('Nameless subcategory');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('categories');
		Schema::dropIfExists('subcategories');
	}
};
