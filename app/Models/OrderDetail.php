<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'orders_detail';
    protected $fillable = [
        'order_id',
        'dish_id',
        'servings'
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id', 'id');
    }
}
