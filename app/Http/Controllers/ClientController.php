<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Response;
use App\Services\ClientService;
use App\Http\Resources\ClientResource;
use App\Http\Requests\{ClientRequest, ListFilterRequest};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function index(ListFilterRequest $request): AnonymousResourceCollection
    {
        return ClientResource::collection($this->clientService->list($request->validated()));
    }

    public function store(ClientRequest $request): ClientResource
    {
        return new ClientResource($this->clientService->create($request->validated()));
    }

    public function show(Client $client): ClientResource
    {
        return new ClientResource($client);
    }

    public function update(Client $client, ClientRequest $request): ClientResource
    {
        return new ClientResource($this->clientService->update($client, $request->validated()));
    }

    public function destroy(Client $client): Response
    {
        $client->delete();

        return response()->noContent();
    }
}
