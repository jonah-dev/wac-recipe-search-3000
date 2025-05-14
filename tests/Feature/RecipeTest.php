<?php

namespace Tests\Feature;

use App\Models\Descriptor;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test that the application returns recipes when no query parameters are present. 
     */
    public function test_recipes_api_returns_successfully(): void
    {
        $response = $this->get('/api/recipes');

        $response->assertStatus(200);
    }

    /**
     * Test that the application returns recipes when a keyword is present.
     */
    public function test_recipes_api_returns_successfully_with_keyword(): void
    {
        // 1. Arrange
        $author = User::factory()->create();
        $recipe = Recipe::factory()
            ->recycle(Ingredient::all())
            ->recycle(Unit::all())
            ->recycle(Descriptor::all())
            ->for($author, 'author')
            ->hasIngredients(3)
            ->hasSteps(5)
            ->create([
                'name' => 'Chicken Curry',
                'description' => 'A delicious chicken curry recipe.',
            ]);
        // Attach a chicken ingredient to the recipe
        $chickenIngredient = Ingredient::firstOrCreate(['name' => 'chicken']);
        RecipeIngredient::factory()->create([
            'recipe_id' => $recipe->id,
            'ingredient_id' => $chickenIngredient->id,
            'unit_id' => 1, // Assuming 1 is the ID of the unit
            'amount' => 2,
        ]); 
        // 2. Act
        $response = $this->get('/api/recipes?keyword=chicken');
        // 3. Assert
        $response->assertStatus(200);
        foreach ($response->json('data') as $recipe) {
            $hasChicken = false;
            foreach ($recipe['ingredients'] as $ingredient) {
                if ($ingredient['ingredient'] === 'chicken') {
                    $hasChicken = true;
                    break;
                }
            }
            $this->assertTrue($hasChicken, 'Recipe does not contain chicken ingredient');
        }

    }

    /**
     * Test that the application returns recipes when a keyword is present.
     */
    public function test_recipes_api_returns_successfully_with_keyword_and_author(): void
    {
        // 1. Arrange
        $targetIngredientName = 'wild alaskan salmon';
        $targetAuthor = User::factory()->create();
        $salmonIngredient = Ingredient::firstOrCreate(['name' => $targetIngredientName]);
        $noisyAuthor = User::factory()->create();

        // Create noisy recipes for target author to filter out
        Recipe::factory()
            ->recycle(Ingredient::all())
            ->recycle(Unit::all())
            ->recycle(Descriptor::all())
            ->for($targetAuthor, 'author')
            ->hasIngredients(3)
            ->hasSteps(5)
            ->count(5)
            ->create();
        // Create a recipe with the target ingredient for the target author
        $targetRecipe = Recipe::factory()
            ->recycle(Ingredient::all())
            ->recycle(Unit::all())
            ->recycle(Descriptor::all())
            ->for($targetAuthor, 'author')
            ->hasIngredients(3)
            ->hasSteps(5)
            ->create([
                'name' => 'Wild Alaskan Seared Salmon',
                'description' => 'A delicious wild Alaskan seared salmon recipe.',
            ]);
        // Attach a salmon ingredient to the recipe
        RecipeIngredient::factory()->create([
            'recipe_id' => $targetRecipe->id,
            'ingredient_id' => $salmonIngredient->id,
            'unit_id' => 1, // Assuming 1 is the ID of the unit
            'amount' => 2,
        ]);
        // Create a recipe with the target ingredient for the noisy author
        $noisySalmonRecipe = Recipe::factory()
            ->recycle(Ingredient::all())
            ->recycle(Unit::all())
            ->recycle(Descriptor::all())
            ->for($noisyAuthor, 'author')
            ->hasIngredients(3)
            ->hasSteps(5)
            ->create([
                'name' => 'Poorly Prepared Wild Alaskan Seared Salmon',
                'description' => 'A mediocre wild Alaskan seared salmon recipe.',
            ]);
        // Attach a salmon ingredient to the recipe
        RecipeIngredient::factory()->create([
            'recipe_id' => $noisySalmonRecipe->id,
            'ingredient_id' => $salmonIngredient->id,
            'unit_id' => 1, // Assuming 1 is the ID of the unit
            'amount' => 2,
        ]);

        // 2. Act
        $htmlEncodedKeyword = rawurlencode($targetIngredientName);
        $response = $this->get('/api/recipes?keyword=' . $htmlEncodedKeyword . '&author=' . $targetAuthor->email);

        // 3. Assert
        $response->assertStatus(200);
        foreach ($response->json('data') as $recipe) {
            $isTargetAuthor = $recipe['author']['email'] === $targetAuthor->email;
            $this->assertTrue($isTargetAuthor, 'Recipe author does not match the target author');

            $hasSalmon = false; 
            foreach ($recipe['ingredients'] as $ingredient) {
                if ($ingredient['ingredient'] === $targetIngredientName) {
                    $hasSalmon = true;
                    break;
                }
            }
            $this->assertTrue($hasSalmon, 'Recipe does not contain wild alaskan salmon ingredient');
        }
    }

    public function test_recipes_api_returns_successfully_with_keyword_and_author_and_ingredient(): void
    {
        // 1. Arrange
        $targetIngredientName = 'potato';
        $targetKeyword = 'scallop';
        $targetEmail = 'foo@bar.com';

        $targetAuthor = User::factory()->create(['email' => $targetEmail]);
        $targetIngredient = Ingredient::firstOrCreate(['name' => $targetIngredientName]);
        $targetRecipe = Recipe::factory()
            ->recycle(Ingredient::all())
            ->recycle(Unit::all())
            ->recycle(Descriptor::all())
            ->for($targetAuthor, 'author')
            ->hasIngredients(3)
            ->hasSteps(5)
            ->create([
                'name' => 'Scallop and Potato Delight',
                'description' => 'A delicious scallop and potato recipe.',
            ]);
        // Attach a potato ingredient to the recipe
        RecipeIngredient::factory()->create([
            'recipe_id' => $targetRecipe->id,
            'ingredient_id' => $targetIngredient->id,
            'unit_id' => 1, // Assuming 1 is the ID of the unit
            'amount' => 2,
        ]);
        
        // 2. Act
        $htmlEncodedKeyword = rawurlencode($targetKeyword);
        $htmlEncodedIngredient = rawurlencode($targetIngredientName);
        $response = $this->get('/api/recipes?keyword=' . $htmlEncodedKeyword . '&author=' . $targetEmail . '&ingredient=' . $htmlEncodedIngredient);

        // 3. Assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $targetRecipe->name,
            'description' => $targetRecipe->description,
        ]);
        $response->assertJsonFragment([
            'ingredient' => $targetIngredientName,
        ]);
        $response->assertJsonFragment([
            'email' => $targetEmail,
        ]);
    } 
}