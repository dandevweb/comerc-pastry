<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'name'       => ['nullable', 'string'],
            'sort_by'    => ['nullable', 'string'],
            'sort_order' => ['nullable', 'string'],
        ]);

        $products = Product::when(
            $request->name,
            fn ($query) => $query->where('name', 'like', '%' . $request->name . '%')
        )
        ->when($request->sort_by, fn ($query) => $query->orderBy($request->sort_by, $request->sort_order ?? 'asc'))
        ->paginate($request->per_page ?? 10);

        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request): ProductResource
    {
        $data = $request->validated();

        $data['photo'] = Storage::putFile('products', $data['photo']);

        $product = Product::create($data);

        return new ProductResource($product);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
