<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Response;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Http\{Request};

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::when(
            $request->name,
            fn ($query) => $query->where('name', 'like', '%' . $request->name . '%')
        )
        ->when($request->sort, fn ($query) => $query->orderBy($request->sort))
        ->paginate($request->per_page ?? 10);

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
