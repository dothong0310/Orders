<?php

namespace App\Services;

use App\Repositories\DishRepository;

class DishService
{
    private DishRepository $dishRepository;

    public function __construct(DishRepository $dishRepository)
    {
        $this->dishRepository = $dishRepository;
    }

    public function search($request)
    {
        $dataFilter = $request->all();
        $dataFilter['meal'] = $request->meal;
        $dataFilter['restaurant'] = $request->restaurant;

        return $this->dishRepository->search($dataFilter);
    }
}
