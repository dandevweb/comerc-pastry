<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\{Response};
use App\Services\ProductService;
use App\Http\Resources\ProductResource;
use App\Http\Requests\{ListFilterRequest, ProductRequest};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index(ListFilterRequest $request): AnonymousResourceCollection
    {
        return ProductResource::collection($this->productService->list($request->validated()));
    }

    public function store(ProductRequest $request): ProductResource
    {
        return new ProductResource($this->productService->create($request->validated()));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }

    public function update(Product $product, ProductRequest $request): ProductResource
    {
        return new ProductResource($this->productService->update($product, $request->validated()));
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
