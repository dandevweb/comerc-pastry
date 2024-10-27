<?php

namespace App\Http\Controllers;

use App\Models\{Order};
use Illuminate\Http\{Request};
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
}
