<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function (Recipe $recipe) {
            $recipe->slug = static::generateUniqueSlug($recipe->name);
        });
    }

    // Relationships
    public function steps()
    {
        return $this->hasMany(RecipeStep::class);
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    // Helpers
    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = Str::slug($name) . '-' . $count++;
        }

        return $slug;
    }
}
