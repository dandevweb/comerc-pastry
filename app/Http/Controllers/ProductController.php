<?php

namespace App\Http\Controllers;

use App\Http\Requests\{ListFilterRequest, ProductRequest};
use Storage;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\{Response};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(ListFilterRequest $request): AnonymousResourceCollection
    {

        $validatedData = $request->validated();
        $filter        = $validatedData['filter'] ?? null;
        $sortBy        = $validatedData['sort_by'] ?? 'name';
        $sortOrder     = $validatedData['sort_order'] ?? 'asc';
        $perPage       = $validatedData['per_page'] ?? 10;

        $products = Product::when(
            $filter,
            fn ($query) => $query->where('name', 'like', "%$filter%")
        )
        ->when($sortBy, fn ($query) => $query->orderBy($sortBy, $sortOrder))
        ->paginate($perPage);

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

    public function update(Product $product, ProductRequest $request): ProductResource
    {
        $data = $request->validated();

        $data['photo'] = Storage::putFile('products', $data['photo']);

        if ($product->photo) {
            Storage::delete($product->photo);
        }

        $product->update($data);

        return new ProductResource($product->fresh());
    }

    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
