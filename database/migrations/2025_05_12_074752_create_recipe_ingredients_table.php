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
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id');
            $table->foreignId('ingredient_id');
            $table->foreignId('descriptor_id')->nullable();
            $table->decimal('amount');
            $table->foreignId('unit_id')->nullable();
            $table->timestamps();
        });

        // Indexes
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->index(['recipe_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
