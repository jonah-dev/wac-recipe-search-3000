<?php

namespace App\Faker;

use Faker\Provider\Base;

class IngredientProvider extends Base
{
    public static function allIngredients(): array
    {
        return [
            'flour',
            'sugar',
            'eggs',
            'milk',
            'butter',
            'salt',
            'pepper',
            'baking powder',
            'vanilla extract',
            'chocolate chips',
            'olive oil',
            'garlic',
            'onion',
            'tomato sauce',
            'cheese',
            'chicken',
            'beef',
            'pork',
            'fish',
            'vegetables',
            'fruit',
            'herbs',
            'spices',
            'nuts',
            'seeds',
            'rice',
            'pasta',
            'bread',
        ];
    }


    public function ingredientName(): string
    {
        return static::randomElement(static::allIngredients());
    }
}
