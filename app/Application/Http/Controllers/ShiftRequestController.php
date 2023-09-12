<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Requests\StoreShiftRequest;
use App\Application\Http\Requests\UpdateShiftRequest;
use App\Application\Http\Resources\ShiftRequestResource;
use App\Domain\Services\ShiftRequestService;
use App\Exceptions\EntryNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\MessageBag;

class ShiftRequestController extends Controller
{
    private ShiftRequestService $ShiftRequestService;

    public function __construct(ShiftRequestService $ShiftRequestService)
    {
        $this->ShiftRequestService = $ShiftRequestService;
    }

    public function index(): AnonymousResourceCollection
    {
        $shiftRequests = $this->ShiftRequestService->getShiftRequestList();
        return ShiftRequestResource::collection($shiftRequests);

    }

    public function show(int $id): ShiftRequestResource
    {
        $shiftRequest = $this->ShiftRequestService->getShiftRequestById($id);
        return new ShiftRequestResource($shiftRequest);
    }

    public function store(StoreShiftRequest $request): MessageBag|ShiftRequestResource
    {
        $shiftRequest = $this->ShiftRequestService->createShiftRequest($request->validated());
        return new ShiftRequestResource($shiftRequest);
    }

    public function update(UpdateShiftRequest $request, int $id): MessageBag|ShiftRequestResource
    {
        $shiftRequest = $this->ShiftRequestService->updateShiftRequest($id, $request->validated());

        return new ShiftRequestResource($shiftRequest);

    }

    public function destroy(int $id): ShiftRequestResource
    {
        $shiftRequest = $this->ShiftRequestService->deleteShiftRequest($id);
        return new ShiftRequestResource($shiftRequest);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function acceptShiftRequest($id): ShiftRequestResource
    {
        $shiftRequest = $this->ShiftRequestService->acceptShiftRequest($id);
        return new ShiftRequestResource($shiftRequest);
    }

    public function rejectShiftRequest($id): ShiftRequestResource
    {
        $shiftRequest = $this->ShiftRequestService->rejectShiftRequest($id);
        return new ShiftRequestResource($shiftRequest);
    }
}
