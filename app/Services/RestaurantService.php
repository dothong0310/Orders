<?php

namespace App\Services;

use App\Repositories\RestaurantRepository;

class RestaurantService
{
    private RestaurantRepository $restaurantRepository;

    public function __construct(RestaurantRepository $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function all()
    {
        return $this->restaurantRepository->all();
    }

    public function search($request)
    {
        $dataFilter = $request->all();
        $dataFilter['meal'] = $request->meal;

        return $this->restaurantRepository->search($dataFilter);
    }
}
