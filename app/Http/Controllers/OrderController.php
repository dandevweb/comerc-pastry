<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Services\OrderService;
use App\Models\{Order};
use App\Http\Requests\OrderRequest;
use Illuminate\Http\{JsonResponse};
use App\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection($this->orderService->list());
    }

    public function store(OrderRequest $request): OrderResource|JsonResponse
    {
        return new OrderResource($this->orderService->save($request->validated()));
    }

    public function show(Order $order): OrderResource
    {
        return new OrderResource($order->load('products', 'client'));
    }

    public function update(Order $order, OrderRequest $request): OrderResource
    {
        return new OrderResource($this->orderService->save($request->validated(), $order));
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }


}
