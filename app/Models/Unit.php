<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    
    // Relationships
    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
}
