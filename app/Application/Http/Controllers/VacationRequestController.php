<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Requests\StoreVacationRequest;
use App\Application\Http\Requests\UpdateVacationRequest;
use App\Application\Http\Resources\VacationRequestResource;
use App\Domain\Services\VacationRequestService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\MessageBag;

class VacationRequestController extends Controller
{
    private VacationRequestService $VacationRequestService;

    public function __construct(VacationRequestService $VacationRequestService)
    {
        $this->VacationRequestService = $VacationRequestService;
    }

    public function index(): AnonymousResourceCollection
    {
        $vacationRequests = $this->VacationRequestService->getVacationRequestList();
        return VacationRequestResource::collection($vacationRequests);
    }

    public function show(int $id): VacationRequestResource
    {
        $vacationRequest = $this->VacationRequestService->getVacationRequestById($id);
        return new VacationRequestResource($vacationRequest);
    }

    public function store(StoreVacationRequest $request): VacationRequestResource|MessageBag
    {
        $vacationRequest = $this->VacationRequestService->createVacationRequest($request->validated());
        return new VacationRequestResource($vacationRequest);

    }

    public function update(UpdateVacationRequest $request, int $id): MessageBag|VacationRequestResource
    {
        $vacationRequest = $this->VacationRequestService->updateVacationRequest($id, $request->validated());
        return new VacationRequestResource($vacationRequest);
    }

    public function destroy(int $id): VacationRequestResource
    {
        $vacationRequest = $this->VacationRequestService->deleteVacationRequest($id);
        return new VacationRequestResource($vacationRequest);
    }

    public function acceptVacationRequest(int $id): VacationRequestResource
    {
        $vacationRequest = $this->VacationRequestService->acceptVacationRequest($id);
        return new VacationRequestResource($vacationRequest);
    }

    public function rejectVacationRequest(int $id): VacationRequestResource
    {
        $vacationRequest = $this->VacationRequestService->rejectVacationRequest($id);
        return new VacationRequestResource($vacationRequest);
    }
}
