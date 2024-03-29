<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function model()
    {
        // TODO: Implement model() method.
        return Order::class;
    }

    public function allWithOrderDetail()
    {
        return $this->model->with('ordersDetail')->get();
    }
}
