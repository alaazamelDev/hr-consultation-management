<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\EventResource;
use App\Domain\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    private EventService $EventService;

    public function __construct(EventService $EventService)
    {
        $this->EventService = $EventService;
    }

    public function index(): AnonymousResourceCollection
    {
        $events = $this->EventService->getEventList();

        return EventResource::collection($events);
    }

    public function show(int $id): JsonResponse
    {
        $event = $this->EventService->getEventById($id);
        return response()->json([
            'data'=> new EventResource($event)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string|max:75',
            'address' => 'required|string|max:100',
            'side_address' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $event = $this->EventService->createEvent(request()->all());
        return response()->json([
            'data'=> new EventResource($event)
            ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'string|max:75',
            'address' => 'string|max:100',
            'side_address' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $event = $this->EventService->updateEvent($id, request()->all());
        return response()->json([
            'data'=> new EventResource($event)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $event = $this->EventService->deleteEvent($id);
        return response()->json([
            'data'=> new EventResource($event)
            ], 200);
    }
}
