<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
    ];

    protected static function booted()
    {
        static::creating(function (RecipeStep $step) {
            $step->step_number = static::where('recipe_id', $step->recipe_id)->count() + 1;
        });
    }

    // Relationships
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
