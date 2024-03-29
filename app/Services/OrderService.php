<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderService
{
    private OrderRepository $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function store($request)
    {
        $data = $request->all();
        $order = $this->orderRepository->create($data);
        foreach ($data['dishes'] as &$dish ) {
            $dish['order_id'] = $order->id;
            $dish['dish_id'] = $dish['idDish'];
        }
        $order->ordersDetail()->createMany($data['dishes']);

        return $order;
    }

    public function allWidthOrderDetail()
    {
        return $this->orderRepository->allWithOrderDetail();
    }
}
