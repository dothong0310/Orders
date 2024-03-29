<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';
    protected $fillable = [
        'name',
    ];

    public function dishes()
    {
        return $this->hasMany(Dish::class, 'restaurant_id', 'id');
    }

    public function scopeWithMeal($query, $meal)
    {
        return $meal ? $query->whereHas('dishes', function ($query) use($meal) {
            $query->whereJsonContains('available_meals', $meal);
        }) : null;
    }
}
