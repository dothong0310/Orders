<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->orderService->allWidthOrderDetail();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->orderService->store($request);
            DB::commit();

            return response()->json([
                'message' => 'Order Success!',
                'status' => true
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
