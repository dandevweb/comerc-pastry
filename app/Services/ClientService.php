<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    public function list(array $filters): LengthAwarePaginator
    {
        $filter    = $filters['filter'] ?? null;
        $sortBy    = $filters['sort_by'] ?? 'name';
        $sortOrder = $filters['sort_order'] ?? 'asc';
        $perPage   = $filters['per_page'] ?? 10;

        return Client::when(
            $filter,
            fn ($query) => $query->where(
                fn ($query) => $query
                    ->where('name', 'like', "%$filter%")
                    ->orWhere('email', 'like', "%$filter%")
            )
        )
        ->when($sortBy, fn ($query) => $query->orderBy($sortBy, $sortOrder))
        ->paginate($perPage);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }
}
