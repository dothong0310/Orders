<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $table = 'dishes';
    protected $fillable = [
        'name',
        'restaurant_id',
        'available_meals'
    ];

    protected function casts()
    {
        return [
            'available_meals' => 'array'
        ];
    }

    public function restaurant ()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'id');
    }

    public function scopeWithRestaurant($query, $restaurant)
    {
        return $restaurant ? $query->whereHas('restaurant', function ($query) use ($restaurant) {
            $query->where('name', $restaurant);
        }) : null;
    }

    public function scopeWithMeal($query, $meal) {
        return $meal ? $query->whereJsonContains('available_meals', $meal) : null;
    }
}
