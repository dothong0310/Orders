<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'number_of_people',
        'meal',
        'restaurant'
    ];

    public function ordersDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
