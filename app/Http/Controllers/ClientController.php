<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Response;
use App\Http\Requests\{ClientRequest, ListFilterRequest};
use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function index(ListFilterRequest $request): AnonymousResourceCollection
    {
        $validatedData = $request->validated();
        $filter        = $validatedData['filter'] ?? null;
        $sortBy        = $validatedData['sort_by'] ?? 'name';
        $sortOrder     = $validatedData['sort_order'] ?? 'asc';
        $perPage       = $validatedData['per_page'] ?? 10;

        $clients = Client::when(
            $filter,
            fn ($query) => $query->where(
                fn ($query) => $query
                    ->where('name', 'like', "%$filter%")
                    ->orWhere('email', 'like', "%$filter%")
            )
        )
        ->when($sortBy, fn ($query) => $query->orderBy($sortBy, $sortOrder))
        ->paginate($perPage);

        return ClientResource::collection($clients);
    }

    public function store(ClientRequest $request): ClientResource
    {
        $client = Client::create($request->validated());

        return new ClientResource($client);
    }

    public function show(Client $client): ClientResource
    {
        return new ClientResource($client);
    }

    public function update(Client $client, ClientRequest $request): ClientResource
    {
        $client->update($request->validated());

        return new ClientResource($client);
    }

    public function destroy(Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }
}
