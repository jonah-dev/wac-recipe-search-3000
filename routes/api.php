<?php

use App\Http\Resources\RecipeCollection;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/recipes', function () {
    $recipes = Recipe::with(['ingredients.ingredient', 'steps', 'author']);
    if (request('author')) {
        $recipes->whereHas('author', function ($query) {
            $query->where('email', request('author'));
        });
    }

    if (request('keyword')) {
        $keyword = '%' . request('keyword') . '%';
        $recipes->where(function ($query) use ($keyword) {
            $query->where('name', 'like', $keyword)
                ->orWhere('description', 'like', $keyword)
                ->orWhereHas('steps', function ($query) use ($keyword) {
                    $query->where('recipe_steps.description', 'like', $keyword);
                })->orWhereHas('ingredients.ingredient', function ($query) use ($keyword) {
                    $query->where('ingredients.name', 'like', $keyword);
                });
        });
    }

    if (request('ingredient')) {
        $recipes->whereHas('ingredients.ingredient', function ($query) {
            $query->where('ingredients.name', 'like', '%' . request('ingredient') . '%');
        });
    }

    return RecipeCollection::make(
        $recipes->paginate(5, page: request('page', 1))
    );
});

Route::get('/recipes/{slug}', function ($slug) {
    $recipe = Recipe::with(['ingredients', 'steps', 'author'])->where('slug', $slug)->firstOrFail();
    return RecipeResource::make($recipe);
});
