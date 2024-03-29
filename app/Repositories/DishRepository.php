<?php

namespace App\Repositories;

use App\Models\Dish;

class DishRepository extends BaseRepository
{
    public function model()
    {
        // TODO: Implement model() method.
        return Dish::class;
    }

    public function search($dataFilter)
    {
        return $this->model
            ->withRestaurant($dataFilter['restaurant'])
            ->withMeal($dataFilter['meal'])
            ->get();
    }
}
