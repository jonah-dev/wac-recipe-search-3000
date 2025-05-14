<?php

namespace Database\Factories;

use App\Faker\DescriptorProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DescriptorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = $this->faker;
        $faker->addProvider(new DescriptorProvider($faker));
        return [
            'name' => $faker->descriptorName()
        ];
    }
}
