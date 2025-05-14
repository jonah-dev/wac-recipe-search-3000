<?php

namespace Database\Factories;

use App\Faker\IngredientProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;
        $faker->addProvider(new IngredientProvider($faker));
        return [
            'name' => $faker->unique()->ingredientName(),
        ];
    }
}
