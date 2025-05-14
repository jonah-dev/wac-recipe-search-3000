<?php

namespace Database\Factories;

use App\Faker\DescriptorProvider;
use App\Models\Descriptor;
use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RecipeIngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->recycle(Ingredient::all());
        $this->recycle(Unit::all());
        $this->recycle(Descriptor::all());

        return [
            'ingredient_id' => Ingredient::factory(),
            'descriptor_id' => null,
            'amount' => $this->faker->randomFloat(2, 0, 100),
            'unit_id' => Unit::factory(),
        ];
    }

    public function withDescriptor(): static
    {
        return $this->state([
            'descriptor_id' => Descriptor::factory(),
        ]);
    }

    public function configure()
    {
        $faker = $this->faker;
        $faker->addProvider(new DescriptorProvider($faker));
        // randomly assign a descriptor to 50% of the ingredients
        return $this->afterCreating(function (RecipeIngredient $recipeIngredient) use ($faker) {
            if ($faker->boolean(50)) {
                $descriptorName = $faker->descriptorName();
                $recipeIngredient->descriptor_id = Descriptor::firstOrCreate(['name' => $descriptorName])->id;
                $recipeIngredient->save();
            }
        });
    }
}
