<?php

namespace App\Repositories;

use App\Models\Restaurant;

class RestaurantRepository extends BaseRepository
{
    public function model()
    {
        // TODO: Implement model() method.
        return Restaurant::class;
    }

    public function search($dataFilter)
    {
        return $this->model
            ->withMeal($dataFilter['meal'])->get();
    }
}
