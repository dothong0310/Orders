<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\DishService;
use Illuminate\Http\Request;

class DishController extends Controller
{
    protected DishService $dishService;

    public function __construct(DishService $dishService)
    {
        $this->dishService = $dishService;
    }

    public function index(Request $request)
    {
        $dishes = $this->dishService->search($request);

        return response()->json($dishes);
    }
}
