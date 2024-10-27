<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Response;
use App\Models\{Order, Product};
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\{JsonResponse, Request};
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

        return $this->save($data);
    }

    public function show(Order $order): OrderResource
    {
        $order->load('products', 'client');

        return new OrderResource($order);
    }

    public function update(Order $order, OrderRequest $request): OrderResource
    {
        $data = $request->validated();

        return $this->save($data, $order);
    }

    public function destroy(Order $order): Response
    {
        $order->delete();

        return response()->noContent();
    }

    private function save(array $data, ?Order $order = null): JsonResponse|OrderResource
    {
        DB::beginTransaction();
        try {
            $total = Product::whereIn('id', $data['products'])->sum('price') / 100;

            $orderSaved = Order::updateOrCreate(['id' => $order->id ?? null], [
                'client_id' => $data['client_id'],
                'total'     => $total,
            ]);

            $orderSaved->products()->sync($data['products']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return new OrderResource($orderSaved);
    }
}
