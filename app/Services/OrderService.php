<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\{Order, Product};
use App\Events\OrderCreatedEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public function list(): LengthAwarePaginator
    {
        return Order::with([
            'products',
            'client',
        ])->paginate(request('per_page', 10));
    }

    public function save(array $data, ?Order $order = null): JsonResponse|Order
    {
        DB::beginTransaction();
        try {
            $total = Product::whereIn('id', $data['products'])->sum('price') / 100;

            $orderSaved = Order::updateOrCreate(['id' => $order->id ?? null], [
                'client_id' => $data['client_id'],
                'total'     => $total,
            ]);

            $orderSaved->products()->sync($data['products']);

            event(new OrderCreatedEvent($orderSaved));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return $orderSaved;
    }

}
