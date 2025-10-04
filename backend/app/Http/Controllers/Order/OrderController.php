<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\IndexOrderRequest;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;

final class OrderController extends Controller
{
    public function __construct(
        public readonly OrderService $orderService
    )
    {
    }

    public function index(IndexOrderRequest $request): JsonResponse
    {
        $orders = $this->orderService->getAll(
            filters:  $request->filters(),
            perPage: $request->perPage(),
            sort: $request->sort(),
        );

        return response()->json($orders);
    }
}
