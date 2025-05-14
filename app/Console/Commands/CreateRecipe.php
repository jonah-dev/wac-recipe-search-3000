<?php

namespace App\Console\Commands;

use App\Models\Descriptor;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeStep;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Console\Command;

/** @package App\Console\Commands */
class CreateRecipe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-recipe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new recipe';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new recipe...');
        $recipeCount = $this->ask('How many recipes do you want to create for this user?');
        if (!is_numeric($recipeCount) || $recipeCount <= 0) {
            $this->error('Please enter a valid number greater than 0.');
            return;
        }
        $recipeCount = (int)$recipeCount;
        $this->info('Creating ' . $recipeCount . ' recipes...');
        $user = User::factory()->create();
        for ($i = 0; $i < $recipeCount; $i++) {
            $recipe = Recipe::factory()
                ->recycle(Ingredient::all())
                ->recycle(Unit::all())
                ->recycle(Descriptor::all())
                ->has(RecipeIngredient::factory()->count(3), 'ingredients')
                ->has(RecipeStep::factory()->count(5), 'steps')
                ->for($user, 'author')
                ->create();
            $this->info('Recipe created successfully!');
            $this->info('Recipe ID: ' . $recipe->id);
            $this->info('Recipe Name: ' . $recipe->name);
            $this->info('Ingredients:');
            foreach ($recipe->ingredients as $recipeIngredient) {
                $ingredient = $recipeIngredient->ingredient;
                $unit = $recipeIngredient->unit;
                $descriptor = $recipeIngredient->descriptor;
                $this->info('- ' . $ingredient->name . ': ' . $recipeIngredient->amount . ' ' . ($unit ? $unit->name : '') . ($descriptor ? ' (' . $descriptor->name . ')' : ''));
            }
            $this->info('Steps:');
            foreach ($recipe->steps as $step) {
                $this->info($step->step_number . ': ' . $step->description);
            }
        }


        $this->info('All recipes created successfully!');
    }
}
