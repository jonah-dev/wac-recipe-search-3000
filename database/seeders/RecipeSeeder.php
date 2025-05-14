<?php

namespace Database\Seeders;

use App\Models\Descriptor;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 recipes with ingredients and steps with a unique user
        Recipe::factory(10)
            ->recycle(Ingredient::all())
            ->recycle(Descriptor::all())
            ->recycle(Unit::all())
            ->has(RecipeIngredient::factory()->count(3), 'ingredients')
            ->has(RecipeStep::factory()->count(5), 'steps')
            ->create();
    }
}