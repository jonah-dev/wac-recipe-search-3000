<?php

namespace Database\Seeders;

use App\Faker\IngredientProvider;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allIngredients = IngredientProvider::allIngredients();
        foreach ($allIngredients as $ingredient) {
            Ingredient::firstOrCreate(['name' => $ingredient]);
        }
    }
}
