<?php

namespace App\Http\Controllers;

use App\Models\{Order, Product};
use DB;
use Illuminate\Http\{JsonResponse, Request};
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::with([
            'products',
            'client',
        ])->paginate($request->per_page ?? 10);

        return OrderResource::collection($orders);
    }

    public function store(OrderRequest $request): OrderResource|JsonResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $total = Product::whereIn('id', $data['products'])->sum('price') / 100;

            $order = Order::create([
                'client_id' => $data['client_id'],
                'total'     => $total,
            ]);

            $order->products()->sync($data['products']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return new OrderResource($order);
    }

    public function show(Order $order): OrderResource
    {
        $order->load('products', 'client');

        return new OrderResource($order);
    }
}
