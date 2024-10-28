<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function list(array $filters): LengthAwarePaginator|Collection
    {
        $filter    = $filters['filter'] ?? null;
        $sortBy    = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $perPage   = $filters['per_page'] ?? 10;
        $columns   = $filters['columns'] ?? null;

        return  Product::when(
            $filter,
            fn ($query) => $query->where('name', 'like', "%$filter%")
        )
        ->when($sortBy, fn ($query) => $query->orderBy($sortBy, $sortOrder))
        ->when(
            $columns,
            fn ($query) => $query->select($columns)->get(),
            fn ($query) => $query->paginate($perPage)
        );
    }

    public function create(array $data): Product
    {
        $data['photo'] = Storage::putFile('products', $data['photo']);

        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $data['photo'] = Storage::putFile('products', $data['photo']);

        if ($product->photo) {
            Storage::delete($product->photo);
        }

        $product->update($data);

        return $product->fresh();
    }
}
